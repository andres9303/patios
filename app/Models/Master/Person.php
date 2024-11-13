<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Model;

class Person extends Model
{
    protected $table = 'people';
    
    protected $fillable = [
        'identification',
        'name',
        'email',
        'phone',
        'address',
        'whatsapp',
        'telegram',
        'text',
        'birth',
        'isClient',
        'isSupplier',
        'isEmployee',
        'state',
    ];
}
