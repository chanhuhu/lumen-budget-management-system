<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RefAllTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // fk for users table
//        Schema::table('users',function (Blueprint $table){
//            $table->foreign('role_id')->references('id')->on('roles')->onUpdate('cascade');
//        });
        // fk for receipt_type table
//        Schema::table('receipt_type',function (Blueprint $table){
//            $table->foreign('receipt_id')->references('id')->on('receipts')->on('roles')->onUpdate('cascade');
//            $table->foreign('type_id')->references('id')->on('types_of_receipts')->on('roles')->onUpdate('cascade');
//        });
        // fk for images table
//        Schema::table('images',function (Blueprint $table){
//            $table->foreign('receipt_id')->references('id')->on('receipts')->on('roles')->onUpdate('cascade');
//        });
        // fk for receipts table
//        Schema::table('receipts',function (Blueprint $table){
//            $table->foreign('activity_id')->references('id')->on('activities')->on('roles')->onUpdate('cascade');
//        });
        //fk for user_activity table
//        Schema::table('user_activity',function (Blueprint $table){
//                    $table->foreign('activity_id')->references('id')->on('activities')->on('roles')->onUpdate('cascade');
//                    $table->foreign('user_id')->references('id')->on('users')->on('roles')->onUpdate('cascade');
//        });
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
