<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReferralRejection extends Model
{
    protected $fillable = [
        'referral_id',
        'email',
        'rejected_at',
    ];

    public function referral()
    {
        return $this->belongsTo(Referral::class, 'referral_id');
    }
}
