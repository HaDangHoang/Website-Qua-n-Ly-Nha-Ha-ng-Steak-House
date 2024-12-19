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
        Schema::create('recipes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('dish_id');
            $table->unsignedBigInteger('ingredient_id');
            $table->decimal('quantity_need', 8, 2); // Thêm độ chính xác cho quantity_need
            $table->timestamps();
            // Thiết lập khóa ngoại cho dish_id
            $table->foreign('dish_id')->references('id')->on('dishes')->onDelete('cascade');
            // Thiết lập khóa ngoại cho ingredient_id
            $table->foreign('ingredient_id')->references('id')->on('ingredients')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('recipes', function (Blueprint $table) {
            // Xóa khóa ngoại trước khi xóa bảng
            $table->dropForeign(['dish_id']);
            $table->dropForeign(['ingredient_id']);
        });

        Schema::dropIfExists('recipes');
    }
};
