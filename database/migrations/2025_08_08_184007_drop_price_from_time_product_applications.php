<?php

// database/migrations/xxxx_xx_xx_xxxxxx_drop_price_from_time_product_applications.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('time_product_applications', function (Blueprint $table) {
            $table->dropColumn('price');
        });
    }

    public function down(): void
    {
        Schema::table('time_product_applications', function (Blueprint $table) {
            $table->integer('price')->nullable();
        });
    }
};

