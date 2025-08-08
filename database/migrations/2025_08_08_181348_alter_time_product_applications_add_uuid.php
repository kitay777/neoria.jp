<?php

// database/migrations/2025_08_08_181348_alter_time_product_applications_add_uuid.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        $table = 'time_product_applications';

        // 既存のユニーク名（環境で違うなら phpMyAdmin で確認して置き換え）
        $uniqueName = 'time_product_applications_time_product_id_user_id_unique';
        $plainIndex = 'tpa_time_product_id_user_id_index';

        // 1) 外部キーが参照するための“通常のインデックス”をまず作る
        $hasPlain = DB::table('information_schema.statistics')
            ->where('table_schema', DB::getDatabaseName())
            ->where('table_name', $table)
            ->where('index_name', $plainIndex)
            ->exists();

        if (! $hasPlain) {
            Schema::table($table, function (Blueprint $t) use ($plainIndex) {
                $t->index(['time_product_id','user_id'], $plainIndex);
            });
        }

        // 2) 複合UNIQUEを削除（FKが使う索引は今作った通常indexに切り替わる）
        $hasUnique = DB::table('information_schema.statistics')
            ->where('table_schema', DB::getDatabaseName())
            ->where('table_name', $table)
            ->where('index_name', $uniqueName)
            ->exists();

        if ($hasUnique) {
            Schema::table($table, function (Blueprint $t) use ($uniqueName) {
                $t->dropUnique($uniqueName);
            });
        }

        // 3) uuid 追加（なければ）
        if (! Schema::hasColumn($table, 'application_uuid')) {
            Schema::table($table, function (Blueprint $t) {
                $t->uuid('application_uuid')->after('id')->unique();
            });
        }
    }

    public function down(): void
    {
        $table = 'time_product_applications';
        $uniqueName = 'time_product_applications_time_product_id_user_id_unique';
        $plainIndex = 'tpa_time_product_id_user_id_index';

        Schema::table($table, function (Blueprint $t) use ($plainIndex) {
            if (Schema::hasColumn($t->getTable(), 'application_uuid')) {
                $t->dropUnique(['application_uuid']);
                $t->dropColumn('application_uuid');
            }
            // 必要なら元の UNIQUE を復活
            // $t->unique(['time_product_id','user_id'], $uniqueName);

            // 作った通常indexは掃除
            $t->dropIndex($plainIndex);
        });
    }
};
