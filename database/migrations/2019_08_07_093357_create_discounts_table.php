<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDiscountsTable extends Migration
{
   /**
    * Run the migrations.
    *
    * @return void
    */
   public function up()
   {
      Schema::create('discounts', function (Blueprint $table) {

         $table->unsignedBigInteger('client_id');
         $table->unsignedBigInteger('product_id');
         $table->primary(['product_id','client_id']);

         $table->foreign('client_id')
            ->references('id')
            ->on('clients')
            ->onUpdate('cascade');

         $table->foreign('product_id')
            ->references('id')
            ->on('products')
            ->onUpdate('cascade');


         $table->float('discount')->default(0);
      });
   }

   /**
    * Reverse the migrations.
    *
    * @return void
    */
   public function down()
   {
      Schema::dropIfExists('discounts');
   }
}
