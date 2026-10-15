<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ProfileTest extends TestCase
{
    use RefreshDatabase;

    public function test_profile_page_shows_user_data(): void
    {
        $user = User::factory()->create([
            'name' => 'Rizky Saria',
            'username' => '@rizkysaria',
            'bio' => 'Penulis',
            'city' => 'Manado',
            'country' => 'Indonesia',
        ]);

        $this->actingAs($user)
            ->get(route('workspace.profile'))
            ->assertOk()
            ->assertSee('Rizky Saria')
            ->assertSee('value="rizkysaria"', false)
            ->assertDontSee('@@')
            ->assertSee('Manado');
    }

    public function test_profile_can_be_updated(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->put(route('workspace.profile.update'), [
                'name' => 'Nama Baru',
                'username' => '@Nama_Baru.1',
                'bio' => 'Bio baru',
                'city' => 'Manado',
                'country' => 'Indonesia',
            ])
            ->assertRedirect(route('workspace.profile'))
            ->assertSessionHas('success');

        $user->refresh();
        $this->assertSame('Nama Baru', $user->name);
        $this->assertSame('nama_baru.1', $user->username);
        $this->assertSame('Bio baru', $user->bio);
        $this->assertSame('Manado', $user->city);
        $this->assertSame('Indonesia', $user->country);
    }

    public function test_user_can_keep_own_username(): void
    {
        $user = User::factory()->create(['username' => 'sayasendiri']);

        $this->actingAs($user)
            ->put(route('workspace.profile.update'), ['name' => 'Saya', 'username' => 'sayasendiri'])
            ->assertSessionHasNoErrors();
    }

    public function test_username_must_be_unique(): void
    {
        User::factory()->create(['username' => 'dipakai']);
        User::factory()->create(['username' => '@legacy']);
        $user = User::factory()->create();

        $this->actingAs($user)
            ->put(route('workspace.profile.update'), ['name' => 'A', 'username' => '@Dipakai'])
            ->assertSessionHasErrors('username');

        $this->actingAs($user)
            ->put(route('workspace.profile.update'), ['name' => 'A', 'username' => 'legacy'])
            ->assertSessionHasErrors('username');
    }

    public function test_validation_rules(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->put(route('workspace.profile.update'), [
                'name' => '',
                'username' => 'ab c!',
                'bio' => str_repeat('a', 161),
            ])
            ->assertSessionHasErrors(['name', 'username', 'bio']);
    }

    public function test_email_cannot_be_changed_from_profile(): void
    {
        $user = User::factory()->create(['email' => 'asli@example.com']);

        $this->actingAs($user)
            ->put(route('workspace.profile.update'), [
                'name' => 'A',
                'username' => 'abc',
                'email' => 'baru@example.com',
            ]);

        $this->assertSame('asli@example.com', $user->fresh()->email);
    }

    public function test_guest_cannot_update_profile(): void
    {
        $this->put(route('workspace.profile.update'), ['name' => 'A', 'username' => 'abc'])
            ->assertRedirect(route('signin'));
    }

    public function test_profile_photo_can_be_uploaded_and_replaced(): void
    {
        Storage::fake('public');
        $user = User::factory()->create();

        $this->actingAs($user)
            ->post(route('workspace.profile.photo.update'), ['photo' => UploadedFile::fake()->image('a.jpg', 300, 300)])
            ->assertRedirect(route('workspace.profile'))
            ->assertSessionHas('success');

        $first = $user->fresh()->profile_photo_path;
        Storage::disk('public')->assertExists($first);

        $this->actingAs($user)
            ->post(route('workspace.profile.photo.update'), ['photo' => UploadedFile::fake()->image('b.png', 300, 300)]);

        Storage::disk('public')->assertMissing($first);
        Storage::disk('public')->assertExists($user->fresh()->profile_photo_path);
        $this->assertStringContainsString('/storage/profile-photos/', $user->fresh()->profile_photo_url);
    }

    public function test_invalid_profile_photo_is_rejected(): void
    {
        Storage::fake('public');
        $user = User::factory()->create();

        $this->actingAs($user)
            ->post(route('workspace.profile.photo.update'), ['photo' => UploadedFile::fake()->create('a.pdf', 10, 'application/pdf')])
            ->assertSessionHasErrors('photo');

        $this->actingAs($user)
            ->post(route('workspace.profile.photo.update'), ['photo' => UploadedFile::fake()->image('big.jpg', 300, 300)->size(3000)])
            ->assertSessionHasErrors('photo');

        $this->assertNull($user->fresh()->profile_photo_path);
    }

    public function test_profile_photo_can_be_deleted(): void
    {
        Storage::fake('public');
        $user = User::factory()->create();

        $this->actingAs($user)
            ->post(route('workspace.profile.photo.update'), ['photo' => UploadedFile::fake()->image('a.jpg', 300, 300)]);
        $path = $user->fresh()->profile_photo_path;

        $this->actingAs($user)
            ->delete(route('workspace.profile.photo.delete'))
            ->assertRedirect(route('workspace.profile'));

        Storage::disk('public')->assertMissing($path);
        $this->assertNull($user->fresh()->profile_photo_path);
    }

    public function test_external_google_photo_is_kept_as_is_and_never_deleted_from_disk(): void
    {
        $user = User::factory()->create(['profile_photo_path' => 'https://lh3.googleusercontent.com/a/x']);

        $this->assertSame('https://lh3.googleusercontent.com/a/x', $user->profile_photo_url);

        $this->actingAs($user)->delete(route('workspace.profile.photo.delete'))->assertRedirect();
        $this->assertNull($user->fresh()->profile_photo_path);
    }
}
