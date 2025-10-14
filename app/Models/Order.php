<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    public $timestamps = false;

    protected $fillable = [
        'user_id', 'customer_id', 'total', 'status', 'customer_name', 'customer_address'
    ];

    // Canonical status codes mapped to Vietnamese labels
    public const STATUS_OPTIONS = [
        'processing' => 'Đang xử lý',
        'shipping'   => 'Đang giao',
        'completed'  => 'Hoàn thành',
        'cancelled'  => 'Đã hủy',
    ];

    public function getStatusLabelAttribute()
    {
        $code = (string)($this->attributes['status'] ?? '');
        return static::STATUS_OPTIONS[$code] ?? $code;
    }

    public function getStatusAttribute($value)
    {
        // Present localized label by default in views
        return static::STATUS_OPTIONS[$value] ?? $value;
    }

    public static function normalizeStatus(?string $value): ?string
    {
        if ($value === null || $value === '') return null;
        $value = trim($value);
        // If already a valid code
        if (array_key_exists($value, static::STATUS_OPTIONS)) return $value;
        // Best‑effort normalize Vietnamese label to ASCII and strip non-letters
        $ascii = @iconv('UTF-8', 'ASCII//TRANSLIT', $value);
        $norm = strtolower(preg_replace('/[^a-z]/', '', $ascii ?: $value));
        // Synonym map
        $candidates = [
            'processing' => ['processing','dangxuly'],
            'shipping'   => ['shipping','danggiao'],
            'completed'  => ['completed','hoanthanh'],
            'cancelled'  => ['cancelled','dahuy'],
        ];
        foreach ($candidates as $code => $keys) {
            if (in_array($norm, $keys, true)) return $code;
        }
        // Fallback: case-insensitive direct label lookup
        $reverse = array_change_key_case(array_flip(static::STATUS_OPTIONS), CASE_LOWER);
        $k = strtolower($value);
        return $reverse[$k] ?? $value; // fallback to raw value
    }
}

