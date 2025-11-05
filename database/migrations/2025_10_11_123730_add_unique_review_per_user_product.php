<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('reviews', function (Blueprint $table) {
            try { $table->unique(['product_id','user_id'], 'reviews_product_user_unique'); } catch (\Throwable $e) {}
        });
    }

    public function down()
    {
        Schema::table('reviews', function (Blueprint $table) {
            try { $table->dropUnique('reviews_product_user_unique'); } catch (\Throwable $e) {}
        });
    }
};

