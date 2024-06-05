<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRelationshipFieldsToPetsTable extends Migration
{
    public function up()
    {
        Schema::table('pets', function (Blueprint $table) {
            $table->unsignedBigInteger('animal_id')->nullable();
            $table->foreign('animal_id', 'animal_fk_9784717')->references('id')->on('animals');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->foreign('user_id', 'user_fk_9808702')->references('id')->on('users');
        });
    }
}
