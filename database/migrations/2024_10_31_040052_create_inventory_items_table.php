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
        Schema::disableForeignKeyConstraints();

        Schema::create('inventory_items', function (Blueprint $table) {
            $table->id();
            // Thay đổi thành unsignedBigInteger
            $table->unsignedBigInteger('ingredient_id'); 
            $table->foreign('ingredient_id')->references('id')->on('ingredients')->onDelete('cascade');
            
            // Thay đổi thành unsignedBigInteger và bỏ unique
            $table->unsignedBigInteger('inventory_transaction_id'); 
            $table->foreign('inventory_transaction_id')->references('id')->on('inventory_transactions')->onDelete('cascade');
            
            $table->decimal('quantity', 10, 2);

            // Thêm created_at và updated_at
            $table->timestamps();

        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventory_items');
    }
};
