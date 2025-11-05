<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('addresses', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('customer_id');
            $table->string('full_name', 120)->nullable();
            $table->string('phone', 30)->nullable();
            $table->string('address_line', 255);
            $table->string('ward', 120)->nullable();
            $table->string('district', 120)->nullable();
            $table->string('province', 120)->nullable();
            $table->boolean('is_default')->default(false);
            $table->dateTime('created_at')->useCurrent();

            $table->foreign('customer_id')->references('id')->on('customers')->cascadeOnDelete()->cascadeOnUpdate();
        });
    }

    public function down()
    {
        Schema::dropIfExists('addresses');
    }
};

