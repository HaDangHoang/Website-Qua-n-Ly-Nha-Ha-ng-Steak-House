<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('refunds', function (Blueprint $table) {
            $table->id(); // ID cho bảng refunds
            $table->unsignedBigInteger('reservation_id'); // ID của bản ghi đặt chỗ
            $table->foreign('reservation_id')->references('id')->on('reservations')->onDelete('cascade');   
            $table->string('account_name')->nullable(); // Tên tài khoản
            $table->string('account_number')->nullable(); // Thay đổi sang kiểu string cho số tài khoản
            $table->decimal('refund_amount', 10, 2); // Số tiền hoàn lại
            $table->string('bank_name')->nullable()->comment('Tên ngân hàng'); // Tên ngân hàng
            $table->string('email')->nullable()->comment('Email liên hệ'); // Email liên hệ
            $table->string('reason')->nullable()->comment('Lý do hoàn lại'); // Lý do hoàn lại
            $table->enum('status', ["Confirmed", "Pending", "Cancelled", "Refund"])->default('Pending'); // Trạng thái hoàn lại
            $table->integer('confirmed_by')->default(0); // Người xác nhận
            $table->timestamp('confirmed_at')->nullable(); // Thời gian xác nhận
            $table->timestamps(); // Thời gian tạo và cập nhật
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('refunds');
    }
};
