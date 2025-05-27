<?php

namespace App\Models\Master;

use App\Models\Config\Item;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Product extends Model
{
    use LogsActivity;
    
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

    public function companies() 
    {
        return $this->belongsToMany(Company::class);
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }
}
