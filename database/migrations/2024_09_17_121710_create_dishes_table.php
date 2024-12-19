<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDishesTable extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('dishes')) {
            Schema::create('dishes', function (Blueprint $table) {
                $table->id(); // bigint, auto-increment
                $table->unsignedBigInteger('category_id'); // bigint, khóa ngoại
                $table->string('name', 255); // varchar(255), không cho phép NULL
                $table->decimal('price', 10, 2); // decimal(10,2)
                $table->string('image', 255)->nullable(); // varchar(255), cho phép NULL
                $table->enum('status', ['available', 'out_of_stock', 'reserved', 'inactive'])->default('available');
                $table->text('description')->nullable(); // text, cho phép NULL
                $table->tinyInteger('is_active')->default(1);
                $table->timestamps(); // created_at và updated_at
                $table->softDeletes(); // deleted_at cho soft delete

                // Thiết lập khóa ngoại
                $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('dishes');
    }
}
