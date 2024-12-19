<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFeedbacksTable extends Migration
{
    public function up()
    {
        Schema::create('feedbacks', function (Blueprint $table) {
            $table->id(); // bigint, auto-increment
            $table->unsignedBigInteger('order_id'); // bigint, không cho phép NULL
            $table->unsignedBigInteger('customer_id'); // bigint, không cho phép NULL
            $table->text('content'); // text, không cho phép NULL
            $table->integer('rating')->default(1); // int, không cho phép NULL, mặc định là 1
            $table->timestamps(); // created_at và updated_at

            // Khóa ngoại
            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
            $table->foreign('customer_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('feedbacks');
    }
}

