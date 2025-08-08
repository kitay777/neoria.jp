<?php

// database/migrations/2025_08_08_161216_add_trade_types_to_time_products_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('time_products', function (Blueprint $table) {
            if (!Schema::hasColumn('time_products', 'trade_types')) {
                $table->json('trade_types')->nullable()->after('duration');
            }
        });

        // 旧カラム trade_type が存在する場合だけバックフィル
        if (Schema::hasColumn('time_products', 'trade_type')) {
            DB::statement("UPDATE time_products 
                           SET trade_types = JSON_ARRAY(trade_type) 
                           WHERE trade_type IS NOT NULL");
        }
    }

    public function down(): void
    {
        Schema::table('time_products', function (Blueprint $table) {
            if (Schema::hasColumn('time_products', 'trade_types')) {
                $table->dropColumn('trade_types');
            }
        });
    }
};

