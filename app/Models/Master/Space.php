<?php

namespace App\Models\Master;

use App\Models\Config\Item;
use Illuminate\Database\Eloquent\Model;

class Space extends Model
{
    protected $table = 'spaces';
    protected $fillable = [
        'name',
        'text',
        'order',
        'state',
        'space_id',
        'item_id',
        'item2_id',
        'cant',
    ];

    public function parentSpace()
    {
        return $this->belongsTo(Space::class, 'space_id');
    }

    public function childSpaces()
    {
        return $this->hasMany(Space::class, 'space_id');
    }
    
    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    public function item2()
    {
        return $this->belongsTo(Item::class);
    }
}
