<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Permission\Traits\HasRoles;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Relations\HasMany;



class User extends Authenticatable implements FilamentUser, MustVerifyEmail
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasApiTokens, HasRoles, LogsActivity;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'google_id',

        'email',
        'password',
        'address',
        'job',
        'office_address',
        'instagram_username',
        'facebook_username',
        'emergency_contact_name',
        'emergency_contact_number',
        'gender',
        'source_info',
        'status',
    ];
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly([
                'name',
                'email',
                'address',
                'job',
                'office_address',
                'instagram_username',
                'facebook_username',
                'emergency_contact_name',
                'emergency_contact_number',
                'gender',
                'source_info',
                'status',
            ]);
    }


    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

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

    /**
     * Get the user's initials
     */
    public function initials(): string
    {
        return Str::of($this->name)
            ->explode(' ')
            ->map(fn(string $name) => Str::of($name)->substr(0, 1))
            ->implode('');
    }

    public function canAccessPanel(Panel $panel): bool
    {
        return true;
    }
    public function userPhotos(): HasMany
    {
        return $this->hasMany(UserPhoto::class, 'user_id', 'id');
    }


    public function userPhoneNumbers(): HasMany
    {
        return $this->hasMany(UserPhoneNumber::class, 'user_id', 'id');
    }


    public function Transactions(): HasMany
    {
        return $this->hasMany(Transaction::class, 'user_id', 'id');
    }
}
