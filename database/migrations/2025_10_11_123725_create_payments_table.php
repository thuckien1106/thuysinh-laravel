<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_id');
            $table->string('method', 30)->default('cod');
            $table->decimal('amount', 10, 2)->default(0);
            $table->string('status', 30)->default('pending');
            $table->string('transaction_id', 120)->nullable();
            $table->dateTime('paid_at')->nullable();

            $table->foreign('order_id')->references('id')->on('orders')->cascadeOnDelete()->cascadeOnUpdate();
        });
    }

    public function down()
    {
        Schema::dropIfExists('payments');
    }
};

