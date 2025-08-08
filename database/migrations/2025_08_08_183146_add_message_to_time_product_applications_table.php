<?php

// database/migrations/xxxx_xx_xx_xxxxxx_add_message_to_time_product_applications_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('time_product_applications', function (Blueprint $table) {
            $table->text('message')->nullable()->after('status');
            // ※ もしまだなら price_snapshot もここで
            // $table->integer('price_snapshot')->nullable()->after('message');
        });
    }
    public function down(): void
    {
        Schema::table('time_product_applications', function (Blueprint $table) {
            $table->dropColumn('message');
            // $table->dropColumn('price_snapshot');
        });
    }
};
