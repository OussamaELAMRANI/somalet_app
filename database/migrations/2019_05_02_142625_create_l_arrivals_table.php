<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLArrivalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('l_arrivals', function (Blueprint $table) {
            $table->unsignedBigInteger('product_id');
            $table->unsignedBigInteger('arrival_id');
            // Possible add Same Product In Diffrent [Rapport, Qte]
            $table->double('qte_facture');
            // qte rapport pour les produit [rouleau/ piece/ box]...
            $table->double('rapport_qte')->nullable();
//            $table->float('fret_douane')->nullable();
            $table->float('price_unit_ht');
//            $table->float('price_unit_ttc');

            $table->double('sell_price')->nullable();

//            $table->float('price_devise')->nullable();
            $table->string('remark')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->date('date_reception')->nullable();

            $table->double('qte_reception')->nullable();
            $table->double('qte_rapport_reception')->nullable();

             $table->double('qte_validation')->nullable();
            $table->double('qte_rapport_validation')->nullable();

            $table->string('remark_reception')->nullable();

            $table->foreign('product_id')
                ->references('id')
                ->on('products')
                ->onUpdate('cascade');

//            $table->foreign('arrival_id')
//                ->references('id')
//                ->on('arrivals')
//                ->onUpdate('cascade');
//            $table->primary(['product_id','arrival_id']);

            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onUpdate('cascade');

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('l_arrivals');
    }
}
