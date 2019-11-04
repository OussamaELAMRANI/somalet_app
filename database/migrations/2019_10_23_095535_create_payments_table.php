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
         $table->date('payed_at')->useCurrent();
         $table->date('date_deadline')->nullable();
         $table->double('amount');
         // Payment, Transfer, Charge
         // PYM => Do by Client (Credit to checkout)
         // TRS => Transfer to a Bank  (Debit from checkout) & (Credit to Bank)
         $table->enum('operation', ['PYM', 'TRS', 'OPR'])->default('PYM');

         // En Caisse
         $table->integer('checkout_now')->default(0);
         $table->integer('done')->default(1); // [IMPAYE, PAYE]
         $table->integer('valid')->default(0); // [Valid, No Valid] By Admin

         $table->string('chq_number')->nullable();
         $table->string('designation')->nullable();

         $table->unsignedBigInteger('typed')->unsigned();
         $table->unsignedBigInteger('client_id')->nullable()->unsigned();
         $table->unsignedBigInteger('adjust_by')->nullable()->unsigned();
         $table->unsignedBigInteger('in_bank')->nullable()->unsigned();
         $table->unsignedBigInteger('from_bank')->nullable()->unsigned();

         $table->foreign('client_id')
            ->references('id')
            ->on('clients')
            ->onUpdate('cascade');

         $table->foreign('in_bank')
            ->references('id')
            ->on('banks')
            ->onUpdate('cascade');

         $table->foreign('from_bank')
            ->references('id')
            ->on('banks')
            ->onUpdate('cascade');

         $table->foreign('adjust_by')
            ->references('id')
            ->on('payments')
            ->onUpdate('cascade');

         $table->foreign('typed')
            ->references('id')
            ->on('payment_types')
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
