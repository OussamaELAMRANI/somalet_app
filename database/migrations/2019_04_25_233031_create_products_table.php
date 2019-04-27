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
            $table->string('reference')->unique();
            $table->string('name');
            $table->unsignedBigInteger('color_id');//todo creation de la table color
            $table->text('description');
            $table->text('remark');
            $table->string('img');
            $table->float('price_sell')->default(0);
            $table->unsignedBigInteger('unit_id'); //todo creation de l'unite
//            todo add marchandise + fabrication type
            $table->enum('type',['MATIERE_PREMIERE','PRODUIT_FINI'])->default('MATIERE_PREMIERE');

            $table->softDeletes();
            $table->timestamps();

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
