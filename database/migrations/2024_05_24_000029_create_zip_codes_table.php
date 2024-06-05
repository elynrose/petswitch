<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateZipCodesTable extends Migration
{
    public function up()
    {
        Schema::create('zip_codes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('zip')->nullable();
            $table->string('lat')->nullable();
            $table->string('long')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }
}