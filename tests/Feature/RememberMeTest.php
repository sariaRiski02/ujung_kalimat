<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class RememberMeTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_receives_remember_me_cookie_when_checked()
    {
        $user = User::factory()->create();

        $response = $this->post(route('signin.post'), [
            'email' => $user->email,
            'password' => 'password',
            'remember' => 'on',
        ]);

        $response->assertRedirect(route('workspace.dashboard'));
        $response->assertCookie(Auth::getRecallerName());
        $this->assertAuthenticatedAs($user);
    }

    public function test_user_does_not_receive_remember_me_cookie_when_not_checked()
    {
        $user = User::factory()->create();

        $response = $this->post(route('signin.post'), [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response->assertRedirect(route('workspace.dashboard'));
        $response->assertCookieMissing(Auth::getRecallerName());
        $this->assertAuthenticatedAs($user);
    }
}
