<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name')->nullable();
            $table->string('last_name')->nullable();
            $table->longText('bio')->nullable();
            $table->string('email')->nullable()->unique();
            $table->string('phone')->nullable();
            $table->datetime('email_verified_at')->nullable();
            $table->string('password')->nullable();
            $table->string('country')->nullable();
            $table->string('state')->nullable();
            $table->string('city')->nullable();
            $table->string('zip')->nullable();
            $table->boolean('conscent')->default(0)->nullable();
            $table->boolean('verified')->default(0)->nullable();
            $table->boolean('email_notification')->default(0)->nullable();
            $table->boolean('sms_notification')->default(0)->nullable();
            $table->integer('banned')->nullable();
            $table->datetime('verified_at')->nullable();
            $table->string('verification_token')->nullable();
            $table->boolean('two_factor')->default(0)->nullable();
            $table->string('two_factor_code')->nullable();
            $table->string('remember_token')->nullable();
            $table->date('expiry')->nullable();
            $table->datetime('two_factor_expires_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }
}
