<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('product_discounts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_id');
            $table->unsignedTinyInteger('percent'); // 1..90
            $table->dateTime('start_at');
            $table->dateTime('end_at');
            $table->string('note', 120)->nullable();

            $table->foreign('product_id')->references('id')->on('products')->cascadeOnDelete();
        });
    }

    public function down()
    {
        Schema::dropIfExists('product_discounts');
    }
};

