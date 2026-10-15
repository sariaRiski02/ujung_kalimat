<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class ResetPasswordTest extends TestCase
{
    use RefreshDatabase;

    public function test_reset_password_page_requires_password_confirmation(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get(route('workspace.profile.reset-password'))
            ->assertRedirect(route('password.confirm'));
    }

    public function test_confirm_route_redirects_back_to_profile_with_modal_flag(): void
    {
        $this->actingAs(User::factory()->create())
            ->get(route('password.confirm'))
            ->assertRedirect(route('workspace.profile'))
            ->assertSessionHas('confirm_password', true);
    }

    public function test_correct_current_password_grants_access_to_reset_page(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->post(route('workspace.profile.confirm-password'), ['current_password' => 'password'])
            ->assertRedirect(route('workspace.profile.reset-password'));

        $this->actingAs($user)
            ->get(route('workspace.profile.reset-password'))
            ->assertOk();
    }

    public function test_wrong_current_password_is_rejected(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->from(route('workspace.profile'))
            ->post(route('workspace.profile.confirm-password'), ['current_password' => 'wrong-password'])
            ->assertRedirect(route('workspace.profile'))
            ->assertSessionHasErrors('current_password', null, 'confirmPassword');

        $this->get(route('workspace.profile.reset-password'))
            ->assertRedirect(route('password.confirm'));
    }

    public function test_password_can_be_updated_after_confirmation(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->withSession(['auth.password_confirmed_at' => time()])
            ->put(route('workspace.profile.reset-password.update'), [
                'password' => 'new-password-123',
                'password_confirmation' => 'new-password-123',
            ])
            ->assertRedirect(route('workspace.profile'))
            ->assertSessionHas('success');

        $this->assertTrue(Hash::check('new-password-123', $user->fresh()->password));

        $this->get(route('workspace.profile.reset-password'))
            ->assertRedirect(route('password.confirm'));
    }

    public function test_password_cannot_be_updated_without_confirmation(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->put(route('workspace.profile.reset-password.update'), [
                'password' => 'new-password-123',
                'password_confirmation' => 'new-password-123',
            ])
            ->assertRedirect(route('password.confirm'));

        $this->assertTrue(Hash::check('password', $user->fresh()->password));
    }

    public function test_new_password_must_be_confirmed_and_long_enough(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->withSession(['auth.password_confirmed_at' => time()])
            ->put(route('workspace.profile.reset-password.update'), [
                'password' => 'short',
                'password_confirmation' => 'different',
            ])
            ->assertSessionHasErrors('password');
    }
}
