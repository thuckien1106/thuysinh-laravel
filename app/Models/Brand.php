<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
    public $timestamps = false;
    protected $fillable = ['name','slug','created_at'];

    public function products()
    {
        return $this->hasMany(Product::class);
    }
}

