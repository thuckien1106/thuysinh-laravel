<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Add FKs for products once categories/brands exist
        Schema::table('products', function (Blueprint $table) {
            if (!Schema::hasColumn('products', 'category_id')) return;
            $table->foreign('category_id')->references('id')->on('categories')->nullOnDelete()->cascadeOnUpdate();
            if (Schema::hasColumn('products', 'brand_id')) {
                $table->foreign('brand_id')->references('id')->on('brands')->nullOnDelete()->cascadeOnUpdate();
            }
        });

        // Add FK for orders.customer_id once customers exists
        Schema::table('orders', function (Blueprint $table) {
            if (Schema::hasColumn('orders', 'customer_id')) {
                $table->foreign('customer_id')->references('id')->on('customers')->nullOnDelete()->cascadeOnUpdate();
            }
        });
    }

    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            try { $table->dropForeign(['category_id']); } catch (\Throwable $e) {}
            try { $table->dropForeign(['brand_id']); } catch (\Throwable $e) {}
        });
        Schema::table('orders', function (Blueprint $table) {
            try { $table->dropForeign(['customer_id']); } catch (\Throwable $e) {}
        });
    }
};

