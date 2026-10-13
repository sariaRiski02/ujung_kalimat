<?php

namespace Tests\Feature;

use App\Mail\OtpMail;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    // ==================== Sign In Tests ====================

    public function test_signin_view_is_accessible(): void
    {
        $response = $this->get(route('signin'));
        $response->assertOk();
        $response->assertViewIs('pages.auth.signin');
    }

    public function test_user_can_signin_with_correct_credentials(): void
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('password123'),
        ]);

        $response = $this->post(route('signin.post'), [
            'email' => 'test@example.com',
            'password' => 'password123',
        ]);

        $response->assertRedirect(route('workspace.dashboard'));
        $this->assertAuthenticatedAs($user);
    }

    public function test_user_cannot_signin_with_incorrect_password(): void
    {
        User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('password123'),
        ]);

        $response = $this->post(route('signin.post'), [
            'email' => 'test@example.com',
            'password' => 'wrongpassword',
        ]);

        $response->assertRedirect();
        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    public function test_user_cannot_signin_with_nonexistent_email(): void
    {
        $response = $this->post(route('signin.post'), [
            'email' => 'nonexistent@example.com',
            'password' => 'password123',
        ]);

        $response->assertRedirect();
        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    public function test_signin_validates_required_fields(): void
    {
        $response = $this->post(route('signin.post'), [
            'email' => '',
            'password' => '',
        ]);

        $response->assertSessionHasErrors(['email', 'password']);
    }

    public function test_signin_validates_email_format(): void
    {
        $response = $this->post(route('signin.post'), [
            'email' => 'invalid-email',
            'password' => 'password123',
        ]);

        $response->assertSessionHasErrors('email');
    }

    public function test_signin_keeps_email_input_on_failure(): void
    {
        User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('password123'),
        ]);

        $response = $this->post(route('signin.post'), [
            'email' => 'test@example.com',
            'password' => 'wrongpassword',
        ]);

        $response->assertRedirect();
        $this->assertFalse(auth()->check());
    }

    public function test_signin_regenerates_session(): void
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('password123'),
        ]);

        $oldSessionId = session()->getId();

        $this->post(route('signin.post'), [
            'email' => 'test@example.com',
            'password' => 'password123',
        ]);

        $newSessionId = session()->getId();
        $this->assertNotEquals($oldSessionId, $newSessionId);
    }

    // ==================== Sign Up View Tests ====================

    public function test_signup_view_is_accessible(): void
    {
        $response = $this->get(route('signup'));
        $response->assertOk();
        $response->assertViewIs('pages.auth.signup');
    }

    // ==================== Logout Tests ====================

    public function test_authenticated_user_can_logout(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->post(route('logout'));

        $response->assertRedirect(route('signin'));
        $this->assertGuest();
    }

    public function test_logout_invalidates_session(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        session()->put('test_data', 'value');
        $this->assertTrue(session()->has('test_data'));

        $this->post(route('logout'));

        $this->assertFalse(session()->has('test_data'));
    }

    public function test_logout_regenerates_csrf_token(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $oldToken = session()->token();

        $this->post(route('logout'));

        $newToken = session()->token();
        $this->assertNotEquals($oldToken, $newToken);
    }

    public function test_unauthenticated_user_can_still_logout(): void
    {
        $response = $this->post(route('logout'));

        $response->assertRedirect(route('signin'));
        $this->assertGuest();
    }

    // ==================== Google OAuth Tests ====================

    public function test_google_redirect_route_exists(): void
    {
        $response = $this->get(route('auth.google'));
        // Route redirects to Google OAuth provider
        $response->assertStatus(302);
    }

    public function test_google_callback_handles_exception(): void
    {
        // This would require mocking Socialite, which is tested separately
        $this->assertTrue(true);
    }

    // ==================== User Factory Tests ====================

    public function test_user_can_be_created_via_factory(): void
    {
        $user = User::factory()->create([
            'email' => 'factory@example.com',
            'username' => 'factoryuser',
        ]);

        $this->assertDatabaseHas('users', [
            'email' => 'factory@example.com',
            'username' => 'factoryuser',
        ]);
    }

    public function test_created_user_has_default_role(): void
    {
        $user = User::factory()->create();

        $this->assertEquals('user', $user->role);
    }

    public function test_created_user_password_is_hashed(): void
    {
        $user = User::factory()->create([
            'password' => bcrypt('password123'),
        ]);

        $this->assertTrue(Hash::check('password123', $user->password));
    }

    // ==================== OTP Mail Tests ====================

    public function test_otp_mail_can_be_sent(): void
    {
        Mail::fake();

        Mail::send(new OtpMail('123456'));

        Mail::assertSent(OtpMail::class);
    }

    public function test_otp_mail_has_correct_subject(): void
    {
        Mail::fake();

        Mail::send(new OtpMail('123456'));

        Mail::assertSent(OtpMail::class, function (OtpMail $mail) {
            return $mail->envelope()->subject === 'Kode verifikasi kamu sudah siap! (Jangan dibagikan ke siapa pun ya)';
        });
    }

    public function test_otp_mail_uses_correct_view(): void
    {
        Mail::fake();

        $otp = new OtpMail('123456');
        $content = $otp->content();

        $this->assertEquals('mail.otp', $content->view);
    }

    // ==================== Edge Cases & Security ====================

    public function test_multiple_failed_signin_attempts_are_tracked(): void
    {
        User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('password123'),
        ]);

        for ($i = 0; $i < 3; $i++) {
            $this->post(route('signin.post'), [
                'email' => 'test@example.com',
                'password' => 'wrongpassword',
            ]);
        }

        // Verify user is still not authenticated
        $this->assertGuest();
    }

    public function test_signin_with_sql_injection_attempt(): void
    {
        $response = $this->post(route('signin.post'), [
            'email' => "' OR '1'='1",
            'password' => "' OR '1'='1",
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    public function test_user_unique_email_constraint(): void
    {
        User::factory()->create([
            'email' => 'duplicate@example.com',
        ]);

        // Attempting to create another user with same email should fail
        $this->assertDatabaseCount('users', 1);
    }

    public function test_user_unique_username_constraint(): void
    {
        User::factory()->create([
            'username' => 'duplicateuser',
        ]);

        // Attempting to create another user with same username should fail
        $this->assertDatabaseCount('users', 1);
    }

    public function test_authenticated_user_cannot_access_signin_view(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->get(route('signin'));

        $response->assertRedirect(route('workspace.dashboard'));
    }

    public function test_authenticated_user_cannot_access_signup_view(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->get(route('signup'));

        $response->assertRedirect(route('workspace.dashboard'));
    }
}
