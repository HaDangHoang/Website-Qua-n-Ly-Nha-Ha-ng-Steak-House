<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::disableForeignKeyConstraints();

        Schema::create('inventory_transactions', function (Blueprint $table) {
            $table->id();
            $table->enum('transaction_type', ["nhập","xuất"]); // Kiểu dữ liệu cho transaction_type
            $table->decimal('total_amount', 10, 2); // Thêm số chữ số cho decimal
            $table->text('description')->nullable();
            $table->unsignedBigInteger('supplier_id')->nullable(); // Thay đổi thành unsignedBigInteger
            $table->foreign('supplier_id')->references('id')->on('suppliers')->onDelete('cascade');
            $table->unsignedBigInteger('staff_id')->nullable(); // Thay đổi thành unsignedBigInteger
            $table->timestamp('created_at')->useCurrent(); // Sử dụng thời gian hiện tại
            $table->timestamp('updated_at')->useCurrent()->nullable(); // Thêm cột updated_at
            $table->enum('status', ["chờ xử lý", "hoàn thành", "Hủy"]); // Kiểu dữ liệu cho status
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Schema::dropIfExists('inventory_transactions');
        Schema::table('inventory_items', function (Blueprint $table) {
            $table->dropForeign(['inventory_transaction_id']);
        });

        // Xóa bảng `inventory_transactions`
        Schema::dropIfExists('inventory_transactions');

        // Xóa bảng `inventory_items` (nếu cần thiết)
        Schema::dropIfExists('inventory_items');
    }
};
