<?php

// database/migrations/xxxx_xx_xx_create_time_product_applications_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('time_product_applications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('time_product_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete(); // 申請者
            $table->unsignedInteger('price'); // 申請時の価格(pts)
            $table->enum('status', ['paid','matched','cancelled'])->default('paid');
            $table->timestamps();
            $table->unique(['time_product_id','user_id']); // 二重申請防止
        });
    }
    public function down(): void {
        Schema::dropIfExists('time_product_applications');
    }
};
