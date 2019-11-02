<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class InsertAllTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        //insert roles table
        DB::table('roles')->insert(
            [[
                'name' => 'Accountant',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],  [
                'name' => 'Employee',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]]
        );

//        insert users table
        DB::table('users')->insert(
            [[
                'role_id' => 1,
                'first' => 'John',
                'last' => 'Doe',
                'email' => 'customer@gmail.com',
                'password' => Hash::make('123456'),
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],  [
                'role_id' => 2,
                'first' => 'Danny',
                'last' => 'Boy',
                'email' => 'danny@gmail.com',
                'password' => Hash::make('easy'),
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]]
        );

        //insert types of receipts table
        DB::table('types_of_receipts')->insert([[
                'name' => 'Purchase_invoices',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],  [
                'name' => 'Material_withdrawal_slips',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]]
        );

        //insert status table
        DB::table('status')->insert(
            [[
                'name' => 'ACTIVE',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ], [
                'name' => 'INACTIVE',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ], [
                'name' => 'DELETED',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ], [
                'name' => 'WAITING',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ], [
                'name' => 'QUEUED',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ], [
                'name' => 'PENDING',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ], [
                'name' => 'PROCESSING',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ], [
                'name' => 'DRAFT',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]]
        );
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
