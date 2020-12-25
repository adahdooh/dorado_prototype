<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->bigInteger('phone')->nullable()->unique();
            $table->string('email')->nullable()->unique();
            $table->string('password');

            $table->string('country')->nullable();
            $table->string('city')->nullable();
            $table->string('gender')->nullable();
            $table->float('length')->nullable();
            $table->float('weight')->nullable();
            $table->integer('age')->nullable();
            $table->string('image')->nullable();


            $table->integer('notification')->default(0);


            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
