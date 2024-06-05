<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRelationshipFieldsToServiceRequestsTable extends Migration
{
    public function up()
    {
        Schema::table('service_requests', function (Blueprint $table) {
            $table->unsignedBigInteger('service_id')->nullable();
            $table->foreign('service_id', 'service_fk_9784757')->references('id')->on('services');
            $table->unsignedBigInteger('pet_id')->nullable();
            $table->foreign('pet_id', 'pet_fk_9784758')->references('id')->on('pets');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->foreign('user_id', 'user_fk_9808703')->references('id')->on('users');
        });
    }
}
