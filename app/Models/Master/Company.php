<?php

namespace App\Models\Master;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    protected $fillable = [
        'name',
        'prefix',
        'address',
        'phone',
        'email',
        'head1',
        'head2',
        'head3',
        'foot1',
        'foot2',
        'foot3',
        'state',
    ];

    public function users()
    {
        return $this->belongsToMany(User::class)->withPivot('role_id');
    }

    
}
