<?php

namespace App\Models;

use App\Models\ReferralRejection;
use Spatie\Activitylog\LogOptions;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable; // Good for auth

// Make sure your model extends Authenticatable if you plan to log them in
class Referral extends Authenticatable 
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        // Step 1 Fields
        'name',
        'email',
        'password',
        
        // Step 2 Fields
        'profile_picture',
        'country',
        'type',
        'niche',

        // Step 3 Fields
        'followers_count',
        'social_platform', // <-- NEW
        'social_link',     // <-- NEW
        'bio',

        // System Fields
        'status',          // We set this in the store method
        'referral_code',   // We will set this on approval

        // Fields for LATER (in Payout Settings)
        // We keep them fillable for our AffiliateSettingsController@update method
        'phone',
        'bank_name',
        'account_number',
        'payment_method',
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
        // 'social_links' is gone, so this is no longer needed
        'email_verified_at' => 'datetime',
    ];

    /**
     * Get the rejection reason for the referral.
     * * (This is a GOOD method, keep it for the admin panel)
     */
    public function rejection()
    {
        return $this->hasOne(ReferralRejection::class, 'referral_id');
    }


    /*
    |--------------------------------------------------------------------------
    | Activity Log Config
    |--------------------------------------------------------------------------
    */
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll() // log all fields
            ->logOnlyDirty() // only log changed fields
            ->useLogName('referral')
            ->setDescriptionForEvent(function (string $eventName) {
                if ($eventName === 'updated' && $this->isDirty('status')) {
                    return "Referral {$this->name} status changed to {$this->status}";
                }
                return "Referral {$this->name} was {$eventName}";
            });
    }
}
