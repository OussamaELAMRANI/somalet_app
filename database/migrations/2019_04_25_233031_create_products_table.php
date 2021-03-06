<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductsTable extends Migration
{
   /**
    * Run the migrations.
    *
    * @return void
    */
   public function up()
   {
      Schema::create('products', function (Blueprint $table) {
         $table->bigIncrements('id');
         $table->unsignedBigInteger('provider_id');
         $table->string('reference');
         $table->string('name')->nullable();
         $table->unsignedBigInteger('color_id')->nullable();
         $table->text('description')->nullable();
         $table->text('remark')->nullable();
         $table->string('img')->nullable();
         $table->float('sell_price')->default(0);
         $table->float('buy_price')->default(0);
         $table->double('alertQte')->default(0);
         $table->unsignedBigInteger('unit_id');
         //metre / rouleau ou piece par box
         $table->double('rapport')->default(0);
//            todo add marchandise + fabrication type
         $table->enum('type', ['MP', 'PF', 'CONS', 'EMB', 'NEG'])->default('MP');
         $table->index('type');

         $table->unsignedBigInteger('user_id')->nullable();
         $table->unsignedBigInteger('subcategory_id')->nullable();
         $table->softDeletes();
         $table->timestamps();

         $table->foreign('subcategory_id')
            ->references('id')
            ->on('sub_categories')
            ->onUpdate('cascade');

         $table->foreign('provider_id')
            ->references('id')
            ->on('providers')
            ->onUpdate('cascade');

         $table->foreign('color_id')
            ->references('id')
            ->on('colors')
            ->onUpdate('cascade');

         $table->foreign('unit_id')
            ->references('id')
            ->on('units')
            ->onUpdate('cascade');

         $table->foreign('user_id')
            ->references('id')
            ->on('users')
            ->onUpdate('cascade');
      });
   }

   /**
    * Reverse the migrations.
    *
    * @return void
    */
   public function down()
   {
      Schema::dropIfExists('products');
   }
}
