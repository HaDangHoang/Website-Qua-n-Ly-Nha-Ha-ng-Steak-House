<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReservationsTable extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('reservations')) {
            Schema::create('reservations', function (Blueprint $table) {
            $table->id(); // bigint, auto-increment
            $table->unsignedBigInteger('customer_id'); // bigint, không cho phép NULL
            // $table->unsignedBigInteger('coupon_id')->nullable(); // bigint, cho phép NULL
            $table->integer('user_phone')->nullable(); // bigint, không
            $table->string('user_name', 255)->nullable(); // varchar(255), không cho phép NULL
            $table->time('reservation_time'); // datetime, không cho phép NULL
            $table->date('reservation_date')->nullable(); // datetime, cho phép NULL
            $table->integer('guest_count')->nullable(); // int, cho phép NULL
            $table->decimal('deposit_amount', 15, 2)->nullable(); // decimal(15,2), cho phép NULL
            $table->enum('status', ['Confirmed', 'Pending','checked-in','Cancelled','Refund'])->default('Pending'); // enum, không cho phép NULL
            $table->string('cancelled_reason', 255)->nullable(); // varchar(255), cho phép NULL
            $table->string('note', 255)->nullable(); // varchar(255), cho phép NULL
            $table->timestamps(); // created_at và updated_at
            $table->softDeletes(); // deleted_at cho soft delete

            // Khóa ngoại
            $table->foreign('customer_id')->references('id')->on('users')->onDelete('cascade'); // Tham chiếu tới bảng users
            // $table->foreign('coupon_id')->references('id')->on('coupons')->onDelete('set null');
        });
    }
}

    public function down()
    {
        Schema::dropIfExists('reservations');
    }
}

