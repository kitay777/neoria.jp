<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
public function up()
{
    Schema::table('time_products', function (Blueprint $table) {
        $table->dropColumn('category');
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('time_products', function (Blueprint $table) {
            //
            $table->string('category')->nullable()->after('duration'); // カテゴリを再追加
            // 外部キー制約は必要に応じて追加
            $table->foreign('category')->references('name')->on('categories')->onDelete('set null');
        });
    }
};
