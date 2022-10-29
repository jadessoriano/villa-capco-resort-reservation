<?php

namespace App\Models;

use Filament\Models\Contracts\FilamentUser;
use Filament\Models\Contracts\HasName;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements FilamentUser, HasName
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    /**
     * The regex for Philippine mobile numbers.
     * The contact number MUST start with either '0' or '+63'
     * then followed by '9' and suffixed by 9 numbers [0-9].
     * @example
     * 09123456789
     * +639123456789
     */
    public const CONTACT_NUMBER_REGEX = "/^(\+63|0)9[0-9]{9}$/";

    /**
     *  The lengths of Philippine mobile numbers are either 11 or 13.
     *  Mobile numbers with '0' prefix has 11 digits while mobile
     *  numbers with '+63' prefix has 13.
     */
    public const CONTACT_NUMBER_MAX_LENGTH = 13;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'contact_number',
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

    /**
     * Get the Ratings for the User.
     */
    public function ratings(): HasMany
    {
        return $this->hasMany(Rating::class);
    }

    /**
     * Get the Reservations for the User.
     */
    public function reservations(): HasMany
    {
        return $this->hasMany(Reservation::class);
    }
    
    /**
     * Returns the full name of the authenticated user in the format:
     * last_name, first_name
     * @return string fullname Full name of the user.
     */
    public function getFullname(): string
    {
        return "{$this->last_name}, {$this->first_name}";
    }

    public function canAccessFilament(): bool
    {
        return str_ends_with($this->email, '@admin.com') && $this->is_admin;
    }

    public function getFilamentName(): string
    {
        return $this->getFullname();
    }
}
