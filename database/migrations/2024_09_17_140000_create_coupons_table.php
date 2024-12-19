<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCouponsTable extends Migration
{
    public function up()
    {
        Schema::create('coupons', function (Blueprint $table) {
            $table->id(); // bigint, auto-increment
            $table->string('code', 255); // varchar(255), không cho phép NULL
            $table->text('description')->nullable(); // text, cho phép NULL
            $table->unsignedBigInteger('max_uses')->nullable(); // bigint, cho phép NULL
            $table->dateTime('start_time'); // datetime, không cho phép NULL
            $table->dateTime('end_time')->nullable(); // datetime, cho phép NULL
            $table->enum('discount_type', ['Percentage', 'Fixed']); // enum, không cho phép NULL
            $table->decimal('discount_amount', 10, 2); // decimal(10,2), không cho phép NULL
            $table->enum('status', ['active', 'inactive', 'expired'])->default('active'); // enum, mặc định là 'active'
            $table->timestamps(); // created_at và updated_at
            $table->softDeletes(); // deleted_at cho soft delete
        });
    }

    public function down()
    {
        Schema::dropIfExists('coupons');
    }
}
