<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id(); // bigint, auto-increment
            $table->string('name', 255);
            $table->string('phone', 255)->nullable(); // Thêm cột phone tại đây
            $table->string('address', 255)->nullable();
            $table->string('email', 255)->unique()->nullable();
            $table->enum('gender', ['male', 'female', 'other'])->nullable();
            $table->date('date_of_birth')->nullable();
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->date('hire_date')->nullable();
            $table->string('position', 255)->nullable();
            // $table->unsignedBigInteger('role_id')->nullable();
            $table->string('avatar', 255)->nullable();
            $table->string('password', 255)->default('None');
            $table->timestamps(); // created_at và updated_at
            $table->softDeletes(); // deleted_at
        });
    }

    public function down()
    {
        Schema::dropIfExists('users');
    }
}

