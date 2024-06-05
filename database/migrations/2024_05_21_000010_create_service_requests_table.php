<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateServiceRequestsTable extends Migration
{
    public function up()
    {
        Schema::create('service_requests', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('zip_code');
            $table->datetime('from');
            $table->datetime('to');
            $table->longText('comments');
            $table->boolean('pending')->default(0)->nullable();
            $table->boolean('closed')->default(0)->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }
}
