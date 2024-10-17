<?php

namespace App\Models\Security;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    protected $fillable = [
        'coddoc',
        'text',
        'route',
        'active',
        'icon',
        'order',
        'menu_id',
    ];

    public function dad()
    {
        return $this->belongsTo(self::class, 'menu_id');
    }

    public function children()
    {
        return $this->hasMany(self::class, 'menu_id');
    }
}
