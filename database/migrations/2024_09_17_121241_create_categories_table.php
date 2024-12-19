<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCategoriesTable extends Migration
{
    public function up()
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id(); // Tự động tạo khóa chính với kiểu bigint và auto-increment
            $table->string('name', 255); // varchar(255), không cho phép NULL
            $table->text('description')->nullable(); // text, cho phép NULL
            $table->timestamps(); // created_at và updated_at
            $table->softDeletes(); // deleted_at cho soft delete
        });
    }

    public function down()
    {
        Schema::dropIfExists('categories');
    }
}
