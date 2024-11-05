<?php

namespace App\Models\Config;

use Illuminate\Database\Eloquent\Model;

class Variable extends Model
{
    protected $fillable = [
        'cod',
        'name',
        'text',
        'concept',
        'value',
        'variable_id'
    ];

    public function variable()
    {
        return $this->belongsTo(Variable::class);
    }
}
