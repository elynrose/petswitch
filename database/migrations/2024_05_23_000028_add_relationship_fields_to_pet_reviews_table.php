<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRelationshipFieldsToPetReviewsTable extends Migration
{
    public function up()
    {
        Schema::table('pet_reviews', function (Blueprint $table) {
            $table->unsignedBigInteger('pet_id')->nullable();
            $table->foreign('pet_id', 'pet_fk_9813579')->references('id')->on('pets');
            $table->unsignedBigInteger('booking_id')->nullable();
            $table->foreign('booking_id', 'booking_fk_9813580')->references('id')->on('bookings');
        });
    }
}
