<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

#[Fillable(['name', 'username', 'email', 'password', 'profile_photo_path', 'bio', 'city', 'country', 'role'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    protected function username(): Attribute
    {
        return Attribute::make(
            get: fn (?string $value) => $value === null ? null : ltrim($value, '@'),
            set: fn (?string $value) => $value === null ? null : strtolower(ltrim(trim($value), '@')),
        );
    }

    protected function profilePhotoUrl(): Attribute
    {
        return Attribute::make(
            get: function () {
                $path = $this->profile_photo_path;

                if (! $path) {
                    return null;
                }

                return Str::startsWith($path, ['http://', 'https://'])
                    ? $path
                    : Storage::disk('public')->url($path);
            },
        );
    }

    public function hasUploadedProfilePhoto(): bool
    {
        return $this->profile_photo_path !== null
            && ! Str::startsWith($this->profile_photo_path, ['http://', 'https://']);
    }

    public function initials(): string
    {
        return Str::of($this->name)
            ->squish()
            ->explode(' ')
            ->filter()
            ->take(2)
            ->map(fn (string $word) => Str::upper(Str::substr($word, 0, 1)))
            ->implode('');
    }

    protected static function booted()
    {
        static::creating(function (User $user) {
            $user->role = 'user';
        });
    }

    public function isSubscriber()
    {
        if ($this->subscription->isEmpty()) {
            return false;
        }

        return $this->subscription
            ->sortByDesc('created_at')
            ->first()?->isActive() ?? false;

    }

    public function article()
    {
        return $this->hasMany(Article::class);
    }

    public function subscription()
    {
        return $this->hasMany(Subscriptions::class);
    }
}
