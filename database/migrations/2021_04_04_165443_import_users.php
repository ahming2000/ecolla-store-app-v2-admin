<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ImportUsers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::table('users')->insert([
            'id' => 1,
            'name' => 'Admin',
            'email' => 'admin@newrainbowmarket.com',
            'role' => 'admin',
            'status' => 'enable',
            'password' => '$2y$10$w9UfBq/NxgJz5IkR8U.upeJwqIQsSHxWn6a4U2NpPVbFrjJH3.Jf.'
        ]);

        DB::table('users')->insert([
            'id' => 2,
            'name' => 'Ah Ming',
            'email' => 'ahming@newrainbowmarket.com',
            'role' => 'admin',
            'status' => 'enable',
            'password' => '$2y$10$o7CFx7WcNgw2yayJMdc9z.MkWIVPbqmaefn3/KkZwknpLrtsd8tD6'
        ]);

        DB::table('users')->insert([
            'id' => 3,
            'name' => 'txe1',
            'email' => 'txe1@newrainbowmarket.com',
            'role' => 'admin',
            'status' => 'enable',
            'password' => '$2y$10$XOC8AkdEmDSQ5.K7qJjvYO6OszzdpvSZfgZwCIobIagyqHqyKu2Bu'
        ]);

        DB::table('users')->insert([
            'id' => 4,
            'name' => 'June',
            'email' => 'june@newrainbowmarket.com',
            'role' => 'admin',
            'status' => 'enable',
            'password' => '$2y$10$ATi9K/dqzv/IzUYBaBk9DODp4jYEExnGyWTmGhuFBWWcKt1RMd5/S' //Password: some_password
        ]);


    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
