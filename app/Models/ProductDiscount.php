<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Carbon\Carbon;

class ProductDiscount extends Model
{
    public $timestamps = false;
    protected $fillable = ['product_id','percent','start_at','end_at','note'];

    protected $casts = [
        'start_at' => 'datetime',
        'end_at' => 'datetime',
    ];

    // Local scopes
    public function scopeActive(Builder $q): Builder
    {
        return $q->where('start_at', '<=', now())
                 ->where('end_at', '>=', now());
    }

    public function scopeUpcoming(Builder $q): Builder
    {
        return $q->where('start_at', '>', now());
    }

    public function scopeExpired(Builder $q): Builder
    {
        return $q->where('end_at', '<', now());
    }

    public function getStatusAttribute(): string
    {
        $now = Carbon::now();
        if ($this->end_at && $now->gt($this->end_at)) return 'expired';
        if ($this->start_at && $now->lt($this->start_at)) return 'upcoming';
        return 'active';
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
