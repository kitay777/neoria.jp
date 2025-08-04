<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('applications', function (Blueprint $table) {
            // 外部キー削除（必要に応じて）
            $table->dropForeign(['work_id']);
            $table->dropForeign(['user_id']);

            // ユニークインデックス削除
            $table->dropUnique('applications_work_id_user_id_unique');
        });
    }

    public function down(): void
    {
        Schema::table('applications', function (Blueprint $table) {
            // ユニーク制約を復元
            $table->unique(['work_id', 'user_id']);

            // 外部キーを復元（必要であれば参照先を明示）
            $table->foreign('work_id')->references('id')->on('works')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }
};

