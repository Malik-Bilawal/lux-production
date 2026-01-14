<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string $password
 * @property string|null $verification_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PendingUser newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PendingUser newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PendingUser query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PendingUser whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PendingUser whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PendingUser whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PendingUser whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PendingUser wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PendingUser whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PendingUser whereVerificationToken($value)
 * @mixin \Eloquent
 */
class PendingUser extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'password',
    ];
}