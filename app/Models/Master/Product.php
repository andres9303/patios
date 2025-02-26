<?php

namespace App\Models\Master;

use App\Models\Config\Item;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $table = 'products';
    protected $fillable = ['code', 'name', 'unit_id', 'state', 'isinventory', 'class', 'type', 'product_id', 'item_id'];

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function item()
    {
        return $this->belongsTo(Item::class);
    }
}
