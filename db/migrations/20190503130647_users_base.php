<?php

use \Migrator\Migrator;

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

class UsersBase extends Migrator
{

    /**
    * Run the migrations.
    *
    * @return void
    */
    public function up() {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('firstname');
            $table->string('lastname');
            $table->string('email');
            $table->timestamps();
        });
    }

     /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('users');
    }
}
