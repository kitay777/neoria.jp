<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
Schema::table('point_logs', function (Blueprint $table) {
    $table->integer('balance')->default(0)->after('amount');
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('point_logs', function (Blueprint $table) {
            //
            $table->dropColumn('balance');
        });
    }
};
