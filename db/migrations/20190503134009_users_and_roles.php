<?php

use Migrator\Migrator;

use Illuminate\Database\Schema\Blueprint;

class UsersAndRoles extends Migrator
{

    /**
    * Run the migrations.
    *
    * @return void
    */
    public function up() {
        $this->schema->create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('firstname');
            $table->string('lastname');
            $table->string('email');
            $table->string('password');
            $table->integer('role_id');
            $table->timestamps();
        });

        $this->schema->create('roles', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('description');
            $table->timestamps();
        });
    }

     /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        $this->schema->dropIfExists('users');
        $this->schema->dropIfExists('roles');
    }
}
