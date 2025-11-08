<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\ProductDiscount;

class CleanupExpiredDiscounts extends Command
{
    protected $signature = 'discounts:cleanup';
    protected $description = 'Xóa các bản ghi giảm giá đã hết hạn';

    public function handle(): int
    {
        $count = ProductDiscount::where('end_at','<', now())->delete();
        $this->info("Đã xóa {$count} giảm giá hết hạn.");
        return self::SUCCESS;
    }
}

