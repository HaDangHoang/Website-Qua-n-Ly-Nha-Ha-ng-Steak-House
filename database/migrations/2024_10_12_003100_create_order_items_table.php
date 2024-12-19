<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_items', function (Blueprint $table) {
            $table->id(); // Tạo trường khóa chính, tự động tăng
            $table->unsignedBigInteger('order_id'); // Khóa ngoại đến bảng orders
            $table->unsignedBigInteger('item_id'); // ID của sản phẩm hoặc dịch vụ
            $table->string('item_type', 10); // Loại item (ví dụ: sản phẩm, dịch vụ)
            $table->integer('quantity'); // Số lượng
            $table->decimal('price', 10, 2); // Đơn giá của từng item
            $table->decimal('total_price', 10, 2); // Tổng giá cho từng item (quantity * price)
            $table->enum('status', ['chờ xử lý', 'đang xử lý', 'hoàn thành', 'hủy'])->default('chờ xử lý');
            $table->string('cancel_reason')->nullable();
            $table->string('informed')->default(0);
            $table->string('processing')->default(0);
            $table->string('completed')->default(0);
            $table->timestamps(); // created_at và updated_at tự động
            // Thiết lập khóa ngoại cho order_id
            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
            $table->foreign('item_id')->references('id')->on('dishes')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('order_items');
    }
}
