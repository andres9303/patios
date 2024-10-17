<?php

namespace App\Models\Security;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $fillable = [
        'name',
    ];

    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'permission_role')
                    ->withPivot('menu_id');
    }

    public function menus()
    {
        return $this->belongsToMany(Menu::class, 'permission_role')
                    ->withPivot('permission_id');
    }

    public function shortcuts()
    {
        return $this->belongsToMany(Menu::class, 'menu_role');
    }
}
