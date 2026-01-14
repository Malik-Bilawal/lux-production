<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $fillable = ['name', 'slug', 'description', 'default_route'];

    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'role_permissions', 'role_id', 'permission_id')
                    ->withPivot('can_view', 'can_edit')
                    ->withTimestamps();
    }

    public function admins()
    {
        return $this->hasMany(Admin::class);
    }
}
