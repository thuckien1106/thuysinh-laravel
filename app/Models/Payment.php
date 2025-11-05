<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $table = 'payments';
    public $timestamps = false;
    protected $fillable = [
        'order_id', 'method', 'amount', 'status', 'transaction_id', 'paid_at'
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}

