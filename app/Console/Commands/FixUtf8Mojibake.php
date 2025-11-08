<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class FixUtf8Mojibake extends Command
{
    protected $signature = 'utf8:fix {--table=} {--columns=} {--apply : Thực hiện UPDATE thay vì chỉ xem trước}';
    protected $description = 'Quét và sửa lỗi mã hóa UTF-8 (mojibake) cho các cột văn bản phổ biến';

    public function handle(): int
    {
        $tables = $this->getTargets();
        $apply = (bool)$this->option('apply');

        foreach ($tables as $table => $columns) {
            if (!DB::getSchemaBuilder()->hasTable($table)) {
                $this->warn("Bỏ qua bảng {$table} (không tồn tại)");
                continue;
            }
            $pk = 'id';
            if (!DB::getSchemaBuilder()->hasColumn($table, $pk)) {
                // cố gắng đoán khóa chính thông dụng
                $pk = DB::getSchemaBuilder()->hasColumn($table, $table.'_id') ? $table.'_id' : 'id';
            }
            foreach ($columns as $col) {
                if (!DB::getSchemaBuilder()->hasColumn($table, $col)) continue;
                $count = DB::table($table)
                    ->where($col, 'like', '%Ã%')
                    ->orWhere($col,'like','%Â%')
                    ->orWhere($col,'like','%áº%')
                    ->orWhere($col,'like','%á»%')
                    ->orWhere($col,'like','%â%')
                    ->count();
                if ($count === 0) continue;
                $this->line("[{$table}.{$col}] phát hiện {$count} dòng nghi ngờ.");
                // lấy 10 dòng xem trước và tính kết quả bằng PHP
                $preview = DB::table($table)
                    ->select([$pk, $col])
                    ->where($col, 'like', '%Ã%')
                    ->orWhere($col,'like','%Â%')
                    ->orWhere($col,'like','%áº%')
                    ->orWhere($col,'like','%á»%')
                    ->orWhere($col,'like','%â%')
                    ->limit(10)->get();
                foreach ($preview as $row) {
                    $fixed = $this->phpFix((string)$row->{$col});
                    if ($fixed !== $row->{$col}) {
                        $this->line(" - #{$row->{$pk}}: '{$row->{$col}}' -> '{$fixed}'");
                    }
                }
                if ($apply) {
                    // Duyệt từng dòng để tránh lỗi 1300 và chỉ cập nhật nếu tốt hơn
                    $cursor = DB::table($table)
                        ->select([$pk, $col])
                        ->where($col, 'like', '%Ã%')
                        ->orWhere($col,'like','%Â%')
                        ->orWhere($col,'like','%áº%')
                        ->orWhere($col,'like','%á»%')
                        ->orWhere($col,'like','%â%')
                        ->orderBy($pk)
                        ->cursor();
                    $updated = 0;
                    foreach ($cursor as $row) {
                        $orig = (string)$row->{$col};
                        $fixed = $this->phpFix($orig);
                        if ($fixed !== $orig && $this->looksBetter($orig, $fixed)) {
                            DB::table($table)->where($pk, $row->{$pk})->update([$col => $fixed]);
                            $updated++;
                        }
                    }
                    $this->info("Đã cập nhật {$updated} dòng cho {$table}.{$col}");
                }
            }
        }

        if (!$apply) $this->comment("Chạy lại với --apply để thực hiện UPDATE.");
        return self::SUCCESS;
    }

    protected function getTargets(): array
    {
        // Nếu chỉ định --table và --columns
        $t = $this->option('table');
        $c = $this->option('columns');
        if ($t && $c) {
            $cols = array_filter(array_map('trim', explode(',', $c)));
            return [$t => $cols];
        }

        // Mặc định: các cột phổ biến trong dự án
        return [
            'products' => ['name','description','short_description','long_description','specs','care_guide'],
            'brands' => ['name'],
            'categories' => ['name'],
            'product_discounts' => ['note'],
        ];
    }

    protected function phpFix(string $val): string
    {
        // cố gắng sửa dạng phổ biến: 'Giáº£m' -> 'Giảm'
        // Bước 1: dùng utf8_decode rồi encode lại
        $step1 = @utf8_decode($val); // UTF-8 -> ISO-8859-1 bytes
        $step1 = is_string($step1) ? $step1 : $val;
        $step2 = @mb_convert_encoding($step1, 'UTF-8', 'ISO-8859-1');
        if (!is_string($step2) || $step2 === '') $step2 = $val;
        return $step2;
    }

    protected function looksBetter(string $orig, string $fixed): bool
    {
        $bad = '/(Ã|Â|áº|á»|â|Â°|Â©|Â·|Â±|Â¢|Â«|Â»|Â®|Â¥|Â¨|Â³|Âµ|Â·|Âº)/u';
        $q1 = preg_match_all($bad, $orig) ?: 0;
        $q2 = preg_match_all($bad, $fixed) ?: 0;
        // cũng không chấp nhận nếu fixed sinh nhiều dấu hỏi
        $q3 = substr_count($fixed, '?');
        return ($q2 < $q1) && $q3 === 0;
    }
}
