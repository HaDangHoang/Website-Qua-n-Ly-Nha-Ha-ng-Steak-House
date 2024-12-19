<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentsTable extends Migration
{
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id(); // bigint, auto-increment
            $table->unsignedBigInteger('reservation_id'); // bigint, không cho phép NULL
            $table->string('bill_id', 255); // varchar(255), không cho phép NULL
            $table->integer('transaction_id')->nullable(); // int, cho phép NULL
            $table->decimal('transaction_amount', 10, 2); // decimal(10,2), không cho phép NULL
            $table->decimal('refund_amount', 15, 2)->nullable(); // decimal(15,2), cho phép NULL
            $table->enum('payment_method', ['Cash', 'Credit Card', 'Online']); // enum, không cho phép NULL
            $table->enum('status', ['Completed', 'Pending', 'Failed'])->default('Pending'); // enum, mặc định là 'Pending'
            $table->enum('transaction_status', ['pending', 'completed', 'failed'])->default('pending'); // enum, mặc định là 'pending'
            $table->timestamps(); // created_at và updated_at
            $table->softDeletes(); // deleted_at cho soft delete

            // Khóa ngoại
            // $table->foreign('reservation_id')->references('id')->on('reservations')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('payments');
    }
}

