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
        Schema::create('kitchens', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_id');
            $table->unsignedBigInteger('item_id');
            $table->string('item_type');
            $table->integer('quantity');
            $table->enum('status', ['đang chế biến', 'chờ cung ứng', 'hoàn thành'])->default('đang chế biến');
            $table->string('cancel')->nullable();
            $table->unsignedBigInteger('canceler')->nullable();
            $table->string('count_cancel')->default(0);
            $table->timestamps();
            $table->foreign('item_id')->references('id')->on('dishes')->onDelete('cascade');
            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
            $table->foreign('canceler')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kitchen');
    }
};
