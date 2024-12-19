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
        Schema::table('refunds', function (Blueprint $table) {
            Schema::table('refunds', function (Blueprint $table) {
                // Sử dụng "CHANGE" để thay đổi cột enum
                $table->enum('status', ['Refund', 'Request_Refund'])->default('Request_Refund')->change();
            });
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('refunds', function (Blueprint $table) {
            // Khôi phục lại các giá trị enum ban đầu nếu cần
            $table->enum('status', ["Confirmed", "Pending", "Cancelled", "Refund"])->default('Pending')->change();
        });
    }
};
