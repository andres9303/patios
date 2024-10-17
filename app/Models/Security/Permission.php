<?php

namespace App\Models\Security;

use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    protected $fillable = [
        'name',
    ];

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'permission_role')
                    ->withPivot('menu_id');
    }
}
