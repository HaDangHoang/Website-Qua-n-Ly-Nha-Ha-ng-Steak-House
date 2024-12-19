<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReservationHistoryTable extends Migration
{
    public function up()
    {
        Schema::create('reservation_history', function (Blueprint $table) {
            $table->id(); // int, auto-increment
            $table->unsignedBigInteger('order_id'); // bigint, không cho phép NULL
            $table->timestamp('change_time'); // timestamp, không cho phép NULL
            $table->enum('status', ['pending', 'confirmed', 'cancelled', 'completed']); // enum, không cho phép NULL
            $table->text('note')->nullable(); // text, cho phép NULL
            $table->timestamps(); // created_at và updated_at, với `CURRENT_TIMESTAMP`
            // Khóa ngoại
            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('reservation_history');
    }
}
