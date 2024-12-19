<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSuppliersTable extends Migration
{
    public function up()
    {
        Schema::create('suppliers', function (Blueprint $table) {
            $table->id(); // bigint, auto-increment
            $table->string('name', 255); // varchar(255), không cho phép NULL
            $table->string('phone', 20); // varchar(20), không cho phép NULL
            $table->string('email', 255)->nullable(); // varchar(255), cho phép NULL
            $table->string('address', 255)->nullable(); // varchar(255), cho phép NULL
            $table->timestamps(); // created_at và updated_at
        });
    }

    public function down()
    {
        Schema::dropIfExists('suppliers');
    }
}
