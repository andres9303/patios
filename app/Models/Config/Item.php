<?php

namespace App\Models\Config;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    protected $fillable = ['id', 'name', 'text', 'order', 'factor', 'catalog_id', 'item_id'];

    public function catalog()
    {
        return $this->belongsTo(Catalog::class);
    }

    public function ref()
    {
        return $this->belongsTo(Item::class);
    }
}
