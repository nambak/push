<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePushReservationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('push_reservations', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('message');
            $table->string('image')->nullable();
            $table->string('link')->nullable();
            $table->date('date')->nullable();
            $table->time('time');
            $table->string('weekday')->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('push_reservations');
    }
}
