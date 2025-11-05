<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductDiscount extends Model
{
    public $timestamps = false;
    protected $fillable = ['product_id','percent','start_at','end_at','note'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}

