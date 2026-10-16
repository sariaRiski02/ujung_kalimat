<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FollowTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_follow(): void
    {
        $target = User::factory()->create();

        $this->post(route('users.follow', $target))->assertRedirect(route('signin'));

        $this->assertDatabaseCount('follows', 0);
    }

    public function test_user_can_follow_and_unfollow(): void
    {
        $user = User::factory()->create();
        $target = User::factory()->create();

        $this->actingAs($user)->post(route('users.follow', $target))->assertRedirect();
        $this->assertTrue($user->isFollowing($target));
        $this->assertTrue($target->followers->contains($user));

        $this->actingAs($user)->delete(route('users.unfollow', $target))->assertRedirect();
        $this->assertFalse($user->fresh()->isFollowing($target));
    }

    public function test_following_twice_does_not_duplicate(): void
    {
        $user = User::factory()->create();
        $target = User::factory()->create();

        $this->actingAs($user)->post(route('users.follow', $target));
        $this->actingAs($user)->post(route('users.follow', $target));

        $this->assertDatabaseCount('follows', 1);
    }

    public function test_cannot_follow_self(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->post(route('users.follow', $user))->assertStatus(422);

        $this->assertDatabaseCount('follows', 0);
    }

    public function test_following_page_lists_followed_users(): void
    {
        $user = User::factory()->create();
        $followed = User::factory()->create(['name' => 'Budi Penulis']);
        User::factory()->create(['name' => 'Orang Asing']);
        $user->follow($followed);

        $this->actingAs($user)->get(route('workspace.following'))
            ->assertOk()
            ->assertSee('Budi Penulis')
            ->assertDontSee('Orang Asing');
    }

    public function test_followers_page_shows_follow_back_state(): void
    {
        $user = User::factory()->create();
        $mutual = User::factory()->create(['name' => 'Mutual Friend']);
        $fan = User::factory()->create(['name' => 'Penggemar Baru']);
        $mutual->follow($user);
        $fan->follow($user);
        $user->follow($mutual);

        $this->actingAs($user)->get(route('workspace.followers'))
            ->assertOk()
            ->assertSee('Mutual Friend')
            ->assertSee('Penggemar Baru')
            ->assertSee('Follow Back')
            ->assertSee('Following');
    }

    public function test_user_can_remove_a_follower(): void
    {
        $user = User::factory()->create();
        $follower = User::factory()->create();
        $follower->follow($user);

        $this->actingAs($user)->delete(route('workspace.followers.remove', $follower))->assertRedirect();

        $this->assertFalse($follower->fresh()->isFollowing($user));
    }

    public function test_remove_follower_does_not_affect_own_following(): void
    {
        $user = User::factory()->create();
        $other = User::factory()->create();
        $user->follow($other);

        $this->actingAs($user)->delete(route('workspace.followers.remove', $other));

        $this->assertTrue($user->isFollowing($other));
    }
}
