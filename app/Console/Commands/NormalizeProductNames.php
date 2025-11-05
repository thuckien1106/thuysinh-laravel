<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Product;

class NormalizeProductNames extends Command
{
    protected $signature = 'products:normalize-names';
    protected $description = 'Chuẩn hóa tên sản phẩm (Title Case, bỏ ký tự thừa, giữ LED/CO2)';

    public function handle(): int
    {
        $normalize = function(string $name): string {
            $n = trim($name);
            $n = preg_replace('/[_-]+/u', ' ', $n);
            $n = preg_replace('/\s+/u', ' ', $n);
            $n = function_exists('mb_convert_case') ? mb_convert_case($n, MB_CASE_TITLE, 'UTF-8') : ucwords(strtolower($n));
            $n = preg_replace('/\b(Led)\b/u', 'LED', $n);
            $n = preg_replace('/\b(Co2)\b/u', 'CO2', $n);
            foreach (['Và','Với','Cho','Của','Trong','Hoặc'] as $w) {
                $n = preg_replace('/\b'.$w.'\b/u', mb_strtolower($w,'UTF-8'), $n);
            }
            // space between number and unit
            $n = preg_replace('/(\d+)\s*(cm|mm|m|l|w)\b/iu', '$1 $2', $n);
            // accents for common Vietnamese words
            $map = [
                '/\b7\s*Mau\b/ui' => 'Bảy Màu',
                '/\bBay Mau\b/ui' => 'Bảy Màu',
                '/\bDuong Xi\b/ui' => 'Dương Xỉ',
                '/\bNguu Mao Chien\b/ui' => 'Ngưu Mao Chiên',
                '/\bRay\b/ui' => 'Ráy',
                '/\bLa Han\b/ui' => 'La Hán',
                '/\bBut Chi\b/ui' => 'Bút Chì',
                '/\bSui\b/ui' => 'Sủi',
                '/\bSuoi\b/ui' => 'Sưởi',
                '/\bLoc\b/ui' => 'Lọc',
                '/\bCa\b/ui' => 'Cá',
            ];
            foreach ($map as $re => $rp) { $n = preg_replace($re, $rp, $n); }
            return $n;
        };

        $count = 0;
        foreach (Product::cursor() as $p) {
            $new = $normalize($p->name ?? '');
            if ($new && $new !== $p->name) {
                $p->name = $new;
                $p->save();
                $count++;
            }
        }
        $this->info("Đã chuẩn hóa {$count} sản phẩm.");
        return Command::SUCCESS;
    }
}
