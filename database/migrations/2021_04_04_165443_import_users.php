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
            'password' => '$2y$10$w9UfBq/NxgJz5IkR8U.upeJwqIQsSHxWn6a4U2NpPVbFrjJH3.Jf.'
        ]);

        DB::table('users')->insert([
            'id' => 2,
            'name' => 'Ah Ming',
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
            'name' => 'user',
            'email' => 'user@newrainbowmarket.com',
            'role' => 'employee',
            'status' => 'enabled',
            'password' => '$2y$10$ATi9K/dqzv/IzUYBaBk9DODp4jYEExnGyWTmGhuFBWWcKt1RMd5/S' //Password: some_password
        ]);

        DB::table('users')->insert([
            'id' => 6,
            'name' => 'Yasuo',
            'email' => 'yasuo@newrainbowmarket.com',
            'role' => 'employee',
            'status' => 'enabled',
            'password' => '$2y$10$ATi9K/dqzv/IzUYBaBk9DODp4jYEExnGyWTmGhuFBWWcKt1RMd5/S' //Password: some_password
        ]);

        DB::table('user_permissions')->insert([
            'user_id' => '5',

            'item_view' => '1',
            'item_create' => '0',
            'item_update' => '0',
            'item_delete' => '1',
            'item_list' => '1',

            'order_view' => '0',
            'order_update' => '1',
            'order_delete' => '1',
            'order_receipt_view' => '1',
            'order_invoice_download' => '0',

            'order_item_view' => '1',
            'order_item_create' => '0',
            'order_item_update' => '0',
            'order_item_delete' => '0',

            'dashboard_view' => '1',

            'setting_view' => '1',
            'setting_item' => '0',
            'setting_order' => '0',
            'setting_pagination' => '0',
            'setting_account' => '0',
        ]);

        DB::table('user_permissions')->insert([
            'user_id' => '6',

            'item_view' => '1',
            'item_create' => '0',
            'item_update' => '0',
            'item_delete' => '1',
            'item_list' => '1',

            'order_view' => '0',
            'order_update' => '1',
            'order_delete' => '1',
            'order_receipt_view' => '1',
            'order_invoice_download' => '0',

            'order_item_view' => '1',
            'order_item_create' => '0',
            'order_item_update' => '0',
            'order_item_delete' => '0',

            'dashboard_view' => '1',

            'setting_view' => '1',
            'setting_item' => '0',
            'setting_order' => '0',
            'setting_pagination' => '0',
            'setting_account' => '0',
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
