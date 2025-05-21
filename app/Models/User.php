<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Laravel\Passport\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];



    protected static function booted(): void
    {
        static::deleted(function (User $user) {

            Log::info("USUARIO BORRADO: ID={$user->id}, Email={$user->email}");

        });

    }
    public function attendedEvents(): BelongsToMany
    {
        return $this->belongsToMany(Event::class, 'event_users', 'user_id', 'event_id')
                    ->withPivot('guests_count')
                    ->withTimestamps()
                    ->orderBy('event_date', 'asc');
    }

    public function createdEvents(): HasMany
    {

        return $this->hasMany(Event::class, 'creator_id', 'id');

    }

    public function isAdmin(): bool
    {
        return $this->hasRole('admin', 'api');
    }
}