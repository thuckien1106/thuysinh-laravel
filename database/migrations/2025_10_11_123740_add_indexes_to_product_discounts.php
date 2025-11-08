<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('product_discounts', function (Blueprint $table) {
            $table->index('product_id', 'idx_product_discounts_product');
            $table->index('start_at', 'idx_product_discounts_start');
            $table->index('end_at', 'idx_product_discounts_end');
        });
    }

    public function down()
    {
        Schema::table('product_discounts', function (Blueprint $table) {
            $table->dropIndex('idx_product_discounts_product');
            $table->dropIndex('idx_product_discounts_start');
            $table->dropIndex('idx_product_discounts_end');
        });
    }
};

