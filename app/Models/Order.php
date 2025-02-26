<?php

namespace App\Models;

use App\Models\Master\Company;
use App\Models\Master\Product;
use App\Models\Master\Space;
use App\Models\Master\Unit;
use App\Models\Project\Activity;
use App\Models\Security\Menu;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'doc_id',
        'menu_id',
        'company_id',
        'product_id',
        'unit_id',
        'cant',
        'value',
        'iva',
        'activity_id',
        'space_id',
        'text',
        'user_id',
    ];

    public function doc()
    {
        return $this->belongsTo(Doc::class);
    }

    public function menu()
    {
        return $this->belongsTo(Menu::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

    public function activity()
    {
        return $this->belongsTo(Activity::class);
    }

    public function space()
    {
        return $this->belongsTo(Space::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
