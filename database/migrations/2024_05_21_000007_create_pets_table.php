<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePetsTable extends Migration
{
    public function up()
    {
        Schema::create('pets', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('breed');
            $table->string('size');
            $table->string('age');
            $table->string('gets_along_with');
            $table->boolean('is_immunized')->default(0)->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }
}
