<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('time_products', function (Blueprint $table) {
            // 位置はお好みで。after('category_id') などにしてOK
            $table->string('prefecture')->nullable()->after('duration');
        });
    }

    public function down(): void
    {
        Schema::table('time_products', function (Blueprint $table) {
            $table->dropColumn('prefecture');
        });
    }
};

