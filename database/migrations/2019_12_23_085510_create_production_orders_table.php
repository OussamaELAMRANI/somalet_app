<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductionOrdersTable extends Migration
{
   /**
    * Run the migrations.
    *
    * @return void
    */
   public function up()
   {
      Schema::create('production_orders', function (Blueprint $table) {

         $table->bigIncrements('id');
         $table->date('date_cmd');
         $table->enum('state',['ATTENTE','VUE','RECEPTION','VALID'])->default('ATTENTE');


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
      Schema::dropIfExists('production_orders');
   }
}
