<?php

// database/migrations/xxxx_xx_xx_xxxxxx_fix_point_logs_fk_to_time_product_applications.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('point_logs', function (Blueprint $table) {
            // まず現在のFKを外す（名前が違っても配列指定ならOK）
            $table->dropForeign(['application_id']);

            // 正しい参照先に付け直す
            $table->foreign('application_id')
                  ->references('id')
                  ->on('time_product_applications')
                  ->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('point_logs', function (Blueprint $table) {
            $table->dropForeign(['application_id']);

            // 元に戻す（必要なら）
            $table->foreign('application_id')
                  ->references('id')
                  ->on('applications')
                  ->cascadeOnDelete();
        });
    }
};
