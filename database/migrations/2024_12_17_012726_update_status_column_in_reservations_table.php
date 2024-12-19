<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateStatusColumnInReservationsTable extends Migration
{
    public function up()
    {
        Schema::table('reservations', function (Blueprint $table) {
            // Cập nhật enum status để thêm giá trị "Pending Refund"
            $table->enum('status', ['Confirmed', 'Pending', 'checked-in', 'Cancelled', 'Refund', 'Pending Refund'])
                  ->default('Pending')
                  ->change();
        });
    }

    public function down()
    {
        // Quay lại enum status ban đầu mà không có "Pending Refund"
        Schema::table('reservations', function (Blueprint $table) {
            $table->enum('status', ['Confirmed', 'Pending', 'checked-in', 'Cancelled', 'Refund'])
                  ->default('Pending')
                  ->change();
        });
    }
}
