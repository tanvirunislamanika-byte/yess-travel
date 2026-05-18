<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Spatie\Permission\Models\Role;
use Laravel\Jetstream\HasTeams;
use Laravel\Sanctum\HasApiTokens;
use App\Notifications\VerificationCodeNotification;
use App\Models\Country;
use App\Models\State;
use App\Models\City;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, HasProfilePhoto, HasTeams, Notifiable, TwoFactorAuthenticatable, HasRoles;

    protected $fillable = [
        'name',
        'email',
        'password',
        'mobile',
        'country',
        'state',
        'city',
        'country_code',
        'profile_photo_path',
        'verification_code',
        'verification_code_expires_at',
        'is_verified',
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'verification_code_expires_at' => 'datetime',
        'is_verified' => 'boolean',
    ];

    protected $appends = [
        'profile_photo_url',
    ];

    // Relationships

    public function type()
    {
        return $this->belongsTo(Role::class, 'role');
    }

    public function membership()
    {
        return $this->belongsTo(Team_user::class, 'id', 'user_id');
    }

    public function countryData()
    {
        return $this->belongsTo(Country::class, 'country', 'id');
    }

    public function stateData()
    {
        return $this->belongsTo(State::class, 'state', 'id');
    }

    public function cityData()
    {
        return $this->belongsTo(City::class, 'city', 'id');
    }

    public function sendEmailVerificationNotification()
    {
        $this->generateAndSendVerificationCode();
    }

    /**
     * Generate and send verification code
     */
    public function generateAndSendVerificationCode()
    {
        // Generate 6-digit verification code
        $verificationCode = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        
        // Set expiration time (15 minutes from now)
        $expiresAt = now()->addMinutes(15);
        
        // Update user with verification code
        $this->update([
            'verification_code' => $verificationCode,
            'verification_code_expires_at' => $expiresAt,
            'is_verified' => false
        ]);
        
        // Send verification code via email
        $this->notify(new \App\Notifications\VerificationCodeNotification($verificationCode));
        
        return $verificationCode;
    }

    /**
     * Check if verification code is valid and not expired
     */
    public function isVerificationCodeValid($code)
    {
        if ($this->verification_code !== $code) {
            return false;
        }
        
        if ($this->verification_code_expires_at && now()->isAfter($this->verification_code_expires_at)) {
            return false;
        }
        
        return true;
    }

    // Accessors

    public function getProfilePhotoUrlAttribute()
    {
        \Log::info('Getting profile photo URL for user: ' . $this->id);
        \Log::info('Profile photo filename: ' . $this->profile_photo_path);
        
        if ($this->profile_photo_path) {
            // Construct the full path from the filename stored in database
            $fullPath = 'profile-photos/' . $this->profile_photo_path;
            $url = asset($fullPath);
            \Log::info('Constructed full path: ' . $fullPath);
            \Log::info('Generated URL: ' . $url);
            return $url;
        }
        
        \Log::info('No photo filename, using default avatar');
        // Fallback to default avatar if no photo
        return 'https://ui-avatars.com/api/?name=' . urlencode($this->name) . '&color=7F9CF5&background=EBF4FF';
    }

    public function getLocationAttribute()
    {
        $location = [];
        if ($this->city) {
            $location[] = $this->city->name ?? '';
        }
        if ($this->state) {
            $location[] = $this->state->name ?? '';
        }
        if ($this->country) {
            $location[] = $this->country->name ?? '';
        }
        return implode(', ', array_filter($location));
    }

    public function getPhoneAttribute()
    {
        return $this->mobile;
    }
}
