<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePhotoUpdatesTable extends Migration
{
    public function up()
    {
        Schema::create('photo_updates', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('comment');
            $table->timestamps();
            $table->softDeletes();
        });
    }
}
