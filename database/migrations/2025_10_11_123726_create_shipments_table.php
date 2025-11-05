<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('shipments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_id');
            $table->string('carrier', 60)->default('local');
            $table->string('tracking_code', 120)->nullable();
            $table->string('status', 30)->default('pending');
            $table->dateTime('shipped_at')->nullable();
            $table->dateTime('delivered_at')->nullable();

            $table->foreign('order_id')->references('id')->on('orders')->cascadeOnDelete()->cascadeOnUpdate();
        });
    }

    public function down()
    {
        Schema::dropIfExists('shipments');
    }
};

