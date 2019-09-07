<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePaymentsTable extends Migration
{
   /**
    * Run the migrations.
    *
    * DESCRIPTION :
    *    Type: ['ESP'] => {checkout_now} && {in_bank}
    *
    *    Type: ['CHQ','EFF'] =>
    *    {
    *       {DeadLine} => En Port Feuille
    *
    *       {Done} => TRUE  {in_bank}
    *       **   => FALSE {adjust_by}
    *    }
    *
    * @return void
    */
   public function up()
   {
      Schema::create('payments', function (Blueprint $table) {
         $table->bigIncrements('id');
         $table->dateTime('payed_at')->useCurrent();
         $table->dateTime('date_deadline')->nullable();
         $table->double('amount');
         // En Caisse
         $table->integer('checkout_now')->default(0);
         $table->enum('type', ['ESP', 'CHQ', 'EFF']);
         $table->integer('done')->default(0); // [IMPAYE, PAYE]

         $table->unsignedBigInteger('cmd_id');
         $table->unsignedBigInteger('adjust_by')->nullable()->unsigned();
         $table->unsignedBigInteger('in_bank')->nullable()->unsigned();

         $table->foreign('cmd_id')
            ->references('id')
            ->on('orders')
            ->onUpdate('cascade');

         $table->foreign('in_bank')
            ->references('id')
            ->on('banks')
            ->onUpdate('cascade');

         $table->foreign('adjust_by')
            ->references('id')
            ->on('payments')
            ->onUpdate('cascade');

         $table->softDeletes();
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
      Schema::dropIfExists('payments');
   }
}
