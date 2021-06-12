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
            'status' => 'enabled',
            'password' => '$2y$10$PMMdxuBaRgc1Syb8ELKP0eoIMRu7UxacCkaNZmY6Q3skvlAbsNcg.', // Isd22*d11Z
        ]);

        DB::table('users')->insert([
            'id' => 2,
            'name' => 'AhMing',
            'email' => 'ahming@newrainbowmarket.com',
            'role' => 'admin',
            'status' => 'enabled',
            'password' => '$2y$10$o7CFx7WcNgw2yayJMdc9z.MkWIVPbqmaefn3/KkZwknpLrtsd8tD6'
        ]);

        DB::table('users')->insert([
            'id' => 3,
            'name' => 'txe1',
            'email' => 'txe1@newrainbowmarket.com',
            'role' => 'admin',
            'status' => 'enabled',
            'password' => '$2y$10$XOC8AkdEmDSQ5.K7qJjvYO6OszzdpvSZfgZwCIobIagyqHqyKu2Bu'
        ]);

        DB::table('users')->insert([
            'id' => 4,
            'name' => 'June',
            'email' => 'june@newrainbowmarket.com',
            'role' => 'admin',
            'status' => 'enabled',
            'password' => '$2y$10$ATi9K/dqzv/IzUYBaBk9DODp4jYEExnGyWTmGhuFBWWcKt1RMd5/S' //Password: some_password
        ]);

        DB::table('users')->insert([
            'id' => 5,
            'name' => 'User',
            'email' => 'user@newrainbowmarket.com',
            'role' => 'admin',
            'status' => 'enabled',
            'password' => '$2y$10$PMMdxuBaRgc1Syb8ELKP0eoIMRu7UxacCkaNZmY6Q3skvlAbsNcg.' // Isd22*d11Z
        ]);

        DB::table('users')->insert([
            'id' => 6,
            'name' => 'mslam',
            'email' => 'mslam@newrainbowmarket.com',
            'role' => 'admin',
            'status' => 'enabled',
            'password' => '$2y$10$H4LbjAg23kul8TnoiWv9juy7oMrvWK/hu6rpWKgI82wrQvBO6N5re' // test123
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
