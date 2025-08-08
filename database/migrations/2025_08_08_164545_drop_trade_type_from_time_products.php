<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// database/migrations/xxxx_xx_xx_xxxxxx_drop_trade_type_from_time_products.php
return new class extends Migration {
    public function up(): void
    {
        Schema::table('time_products', function (Blueprint $table) {
            if (Schema::hasColumn('time_products', 'trade_type')) {
                $table->dropColumn('trade_type');
            }
        });
    }
    public function down(): void
    {
        Schema::table('time_products', function (Blueprint $table) {
            $table->string('trade_type')->nullable();
        });
    }
};

