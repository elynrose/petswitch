<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRelationshipFieldsToCreditsTable extends Migration
{
    public function up()
    {
        Schema::table('credits', function (Blueprint $table) {
            $table->unsignedBigInteger('service_request_id')->nullable();
            $table->foreign('service_request_id', 'service_request_fk_9784795')->references('id')->on('service_requests');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->foreign('user_id', 'user_fk_9808700')->references('id')->on('users');
        });
    }
}
