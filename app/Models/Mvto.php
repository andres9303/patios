<?php

namespace App\Models;

use App\Models\Config\Item;
use App\Models\Master\Product;
use App\Models\Master\Unit;
use App\Models\Project\Activity;
use Illuminate\Database\Eloquent\Model;

class Mvto extends Model
{
    protected $table = 'mvtos';
    protected $fillable = [
        'doc_id', 
        'product_id', 
        'unit_id', 
        'cant', 
        'saldo', 
        'valueu', 
        'iva', 
        'valuet', 
        'text', 
        'state', 
        'product2_id', 
        'unit2_id', 
        'cant2', 
        'saldo2', 
        'valueu2', 
        'iva2', 
        'valuet2', 
        'text2', 
        'item_id', 
        'mvto_id', 
        'concept', 
        'ref', 
        'costu', 
        'activity_id'
    ];

    public function doc()
    {
        return $this->belongsTo(Doc::class);
    }
    
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

    public function product2()
    {
        return $this->belongsTo(Product::class);
    }

    public function unit2()
    {
        return $this->belongsTo(Unit::class);
    }

    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    public function mvto()
    {
        return $this->belongsTo(Mvto::class);
    }

    public function activity()
    {
        return $this->belongsTo(Activity::class);
    }
}
