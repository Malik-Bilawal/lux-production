<?php

namespace App\Models;

use Illuminate\Support\Facades\Cache;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Admin extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'name', 'email', 'password', 'role_id', 'profile_pic',   'current_session_id', 'is_blocked',
        'last_login_at',
        'last_login_ip',
    ];
    protected $casts = [
        'last_login_at' => 'datetime',
    ];
    protected $hidden = ['password'];

    // Admins role
    public function role()
    {
        return $this->belongsTo(\App\Models\Role::class, 'role_id');
    }

    public function permissions()
{
    return $this->belongsToMany(Permission::class, 'role_permissions', 'role_id', 'permission_id')
                ->withPivot('can_view', 'can_edit')
                ->withTimestamps();
}

    public function hasPermission($slug, $type = 'view')
    {
        if ($this->role && $this->role->slug === 'super_admin') {
            return true;
        }

        $this->loadMissing('role.permissions');

        if ($this->role && $this->role->relationLoaded('permissions')) {
            $rolePerms = $this->role->permissions->filter(function ($perm) use ($type) {
                return $perm->pivot && ($type === 'view' ? $perm->pivot->can_view : $perm->pivot->can_edit);
            })->pluck('slug');

            if ($rolePerms->contains($slug)) {
                return true;
            }
        }

        return false; 
    }

    public function isOnline()
    {
        return Cache::has('admin-is-online-' . $this->id);
    }
}