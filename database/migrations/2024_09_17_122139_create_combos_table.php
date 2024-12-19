<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCombosTable extends Migration
{
    public function up()
    {
        Schema::create('combos', function (Blueprint $table) {
            $table->id(); // bigint, auto-increment
            $table->string('name', 255); // varchar(255), không cho phép NULL
            $table->text('description')->nullable(); // text, cho phép NULL
            $table->decimal('price', 10, 2); // decimal(10,2), không cho phép NULL
            $table->string('image', 255)->nullable(); // varchar(255), cho phép NULL
            $table->integer('quantity_dishes')->nullable(); // int, cho phép NULL
            $table->tinyInteger('is_active')->default(1);
            
            $table->timestamps(); // created_at và updated_at
            $table->softDeletes(); // deleted_at cho soft delete
        });
    }

    public function down()
    {
        Schema::dropIfExists('combos');
    }
}

