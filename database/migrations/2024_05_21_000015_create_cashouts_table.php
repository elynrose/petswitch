<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCashoutsTable extends Migration
{
    public function up()
    {
        Schema::create('cashouts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('credits');
            $table->decimal('amount', 15, 2);
            $table->boolean('issued')->default(0)->nullable();
            $table->string('tracking')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }
}
