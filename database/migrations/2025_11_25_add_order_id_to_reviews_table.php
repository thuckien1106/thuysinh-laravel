<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('reviews', function (Blueprint $table) {
            // Thêm cột order_id
            if (!Schema::hasColumn('reviews', 'order_id')) {
                $table->unsignedBigInteger('order_id')->nullable()->after('user_id');
                $table->foreign('order_id')->references('id')->on('orders')->cascadeOnDelete()->cascadeOnUpdate();
            }
        });
    }

    public function down()
    {
        Schema::table('reviews', function (Blueprint $table) {
            if (Schema::hasColumn('reviews', 'order_id')) {
                // Xóa foreign key nếu tồn tại
                try {
                    $table->dropForeign('reviews_order_id_foreign');
                } catch (\Throwable $e) {
                    // Foreign key không tồn tại, bỏ qua
                }
                // Không xóa cột, chỉ xóa foreign key
            }
        });
    }
};
