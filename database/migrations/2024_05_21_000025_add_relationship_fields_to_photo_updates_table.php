<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRelationshipFieldsToPhotoUpdatesTable extends Migration
{
    public function up()
    {
        Schema::table('photo_updates', function (Blueprint $table) {
            $table->unsignedBigInteger('booking_id')->nullable();
            $table->foreign('booking_id', 'booking_fk_9808719')->references('id')->on('bookings');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->foreign('user_id', 'user_fk_9808722')->references('id')->on('users');
        });
    }
}
