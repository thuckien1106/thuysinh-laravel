<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    protected $table = 'addresses';
    public $timestamps = false;
    protected $fillable = [
        'customer_id', 'full_name', 'phone', 'address_line', 'ward', 'district', 'province', 'is_default'
    ];
}
