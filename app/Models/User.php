<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'username', 'email', 'password'])]
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

    protected static function booted()
    {
        static::creating(function(User $user){
            $user->role = 'user';
        });
    }
    

    public function isSubscriber(){
        if($this->subscription->isEmpty()){
            return false;
        }
        return $this->subscription
                ->sortByDesc('created_at')
                ->first()?->isActive() ?? false;
        
    }

    public function article(){
        return $this->hasMany(Article::class);
    }


    public function subscription(){
        return $this->hasMany(Subscriptions::class);
    }
}
