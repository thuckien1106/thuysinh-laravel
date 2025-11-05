<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Shipment extends Model
{
    protected $table = 'shipments';
    public $timestamps = false;
    protected $fillable = [
        'order_id', 'carrier', 'tracking_code', 'status', 'shipped_at', 'delivered_at'
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}

