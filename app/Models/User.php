<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'company_id', 'name', 'email', 'password', 'two_factor_secret', 
        'two_factor_recovery_codes', 'two_factor_confirmed', 'two_factor_email_confirmed',
        'image', 'country_phonecode', 'mobile', 'gender', 'salutation', 'locale',
        'status', 'login', 'onesignal_player_id', 'last_login', 'email_notifications',
        'country_id', 'dark_theme', 'rtl', 'two_fa_verify_via', 'two_factor_code',
        'two_factor_expires_at', 'admin_approval', 'permission_sync', 
        'google_calendar_status', 'remember_token', 'customised_permissions', 
        'stripe_id', 'pm_type', 'pm_last_four', 'trial_ends_at', 'headers', 
        'register_ip', 'location_details', 'inactive_date', 'twitter_id'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'two_factor_confirmed' => 'boolean',
        'two_factor_email_confirmed' => 'boolean',
        'last_login' => 'datetime',
        'two_factor_expires_at' => 'datetime',
        'email_notifications' => 'boolean',
        'dark_theme' => 'boolean',
        'rtl' => 'boolean',
        'admin_approval' => 'boolean',
        'permission_sync' => 'boolean',
        'google_calendar_status' => 'boolean',
        'customised_permissions' => 'boolean',
        'inactive_date' => 'date',
        'trial_ends_at' => 'datetime',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token', 'two_factor_secret', 'two_factor_recovery_codes'
    ];

    /**
     * Relationship to the company this user belongs to.
     *
     * @return BelongsTo
     */
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * Relationship to the country this user is associated with.
     *
     * @return BelongsTo
     */
    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }
}