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
        Schema::create('works', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // 発注者
            $table->string('title');           // 仕事タイトル
            $table->text('description');       // 詳細説明
            $table->string('location')->nullable(); // 勤務地やリモート可など
            $table->integer('price');          // 価格（報酬）
            $table->date('deadline');          // 応募期限
            $table->boolean('is_overseas_allowed')->default(false);
            $table->boolean('is_verified_by_client')->default(false);
            $table->enum('status', ['open', 'closed'])->default('open'); // 募集中かどうか
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('works');
    }
};
