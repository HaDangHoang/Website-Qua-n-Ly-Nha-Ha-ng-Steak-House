<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::disableForeignKeyConstraints();

        Schema::create('inventory_stocks', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('ingredient_id'); // Thay đổi từ integer sang unsignedBigInteger
            $table->foreign('ingredient_id')->references('id')->on('ingredients')->onDelete('cascade');
            $table->integer('quantity_stock');
            $table->integer('quantity_reserved')->default(0);
            $table->timestamp('last_update'); // Đổi từ bigInteger sang timestamp
            $table->timestamps();
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventory_stock');
    }
};
