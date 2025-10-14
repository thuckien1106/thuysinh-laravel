<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    public $timestamps = false;
    protected $fillable = [
        'product_id','user_id','rating','content','created_at'
    ];
}

