<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTablesTable extends Migration
{
    public function up()
    {
        Schema::create('tables', function (Blueprint $table) {
            $table->id();  // bigint, auto-increment
            $table->enum('area', ['Tầng 1', 'Tầng 2', 'Tầng 3'])->charset('utf8mb4')->collation('utf8mb4_unicode_ci');
            $table->integer('table_number');
            $table->enum('status', ['Available','Pending','Reserved', 'Occupied'])->charset('utf8mb4')->collation('utf8mb4_unicode_ci');
            $table->timestamps(); // created_at, updated_at
            $table->softDeletes(); // deleted_at
            $table->unsignedBigInteger('parent_id')->nullable();
        });

    }

    public function down()
    {
        Schema::dropIfExists('tables');
    }
}
