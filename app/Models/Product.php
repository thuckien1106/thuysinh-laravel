<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $fillable = [
        'name','description','short_description','long_description','specs','care_guide',
        'price','quantity','image','category_id','brand_id'
    ];

    // Relationships
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function orderDetails()
    {
        return $this->hasMany(OrderDetail::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function discounts()
    {
        return $this->hasMany(ProductDiscount::class);
    }

    public function activeDiscount()
    {
        return $this->hasOne(ProductDiscount::class)
            ->where('start_at','<=', now())
            ->where('end_at','>=', now())
            ->latest('end_at');
    }

    public function getFinalPriceAttribute()
    {
        $percent = optional($this->activeDiscount)->percent;
        if ($percent && $percent > 0) {
            return round((float)$this->price * (100 - $percent) / 100, 2);
        }
        return (float)$this->price;
    }
}
