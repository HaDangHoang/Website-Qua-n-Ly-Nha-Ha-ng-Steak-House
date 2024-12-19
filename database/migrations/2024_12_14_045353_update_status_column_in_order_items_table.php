<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('order_items', function (Blueprint $table) {
            $table->string('status', 20)->change();
        });

        DB::statement("ALTER TABLE order_items MODIFY COLUMN status ENUM('chưa yêu cầu','chờ xử lý', 'đang xử lý', 'hoàn thành', 'hủy') DEFAULT 'chờ xử lý'");
    }

    public function down()
    {
        DB::statement("ALTER TABLE order_items MODIFY COLUMN status ENUM('chờ xử lý', 'đang xử lý', 'hoàn thành', 'hủy') DEFAULT 'chờ xử lý'");
    }
};
