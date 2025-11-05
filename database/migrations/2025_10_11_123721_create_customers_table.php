<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('full_name', 120);
            $table->string('email', 120)->nullable()->unique(false);
            $table->string('phone', 20)->nullable();
            $table->enum('gender', ['Nam','Nữ','Khác'])->default('Khác');
            $table->date('birthday')->nullable();
            $table->string('address', 255)->nullable();
            $table->dateTime('created_at')->useCurrent();
        });
    }

    public function down()
    {
        Schema::dropIfExists('customers');
    }
};

