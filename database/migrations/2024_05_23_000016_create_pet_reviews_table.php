<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePetReviewsTable extends Migration
{
    public function up()
    {
        Schema::create('pet_reviews', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->longText('comment');
            $table->integer('rating');
            $table->timestamps();
            $table->softDeletes();
        });
    }
}
