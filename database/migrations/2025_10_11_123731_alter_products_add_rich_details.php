<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            if (!Schema::hasColumn('products', 'short_description')) {
                $table->string('short_description', 255)->nullable()->after('description');
            }
            if (!Schema::hasColumn('products', 'long_description')) {
                $table->longText('long_description')->nullable()->after('short_description');
            }
            if (!Schema::hasColumn('products', 'specs')) {
                $table->text('specs')->nullable()->after('long_description');
            }
            if (!Schema::hasColumn('products', 'care_guide')) {
                $table->text('care_guide')->nullable()->after('specs');
            }
        });
    }

    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            foreach (['care_guide','specs','long_description','short_description'] as $col) {
                if (Schema::hasColumn('products', $col)) { $table->dropColumn($col); }
            }
        });
    }
};

