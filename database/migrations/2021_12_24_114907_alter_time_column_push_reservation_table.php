<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTimeColumnPushReservationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('push_reservations', function (Blueprint $table) {
            $table->string('time')->change()->nullable();
            $table->dateTime('date')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('push_reservations', function (Blueprint $table) {
            $table->time('time')->change();
            $table->date('date')->change();
        });
    }
}
