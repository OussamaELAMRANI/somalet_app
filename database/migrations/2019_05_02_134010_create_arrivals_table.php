<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateArrivalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('arrivals', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('n_dossier');
//            $table->string('n_facture')->unique();
//            $table->double('price_devise')->nullable();
//            $table->double('cours_change')->nullable();
//            $table->double('taux_marge')->nullable();
            $table->date('date_facture')->nullable();
            $table->enum('type',['INTERNE','NATIONAL','INTERNATIONAL'])->default('INTERNE');

            $table->enum('state',['ATTENTE','VUE','RECEPTION','VALID'])->default('ATTENTE');

//            $table->double('price_provider')->nullable(); //en dh
//            $table->double('transitaire')->nullable();
//            $table->double('transitaire_tva')->nullable();
//            $table->double('transitaire_ttc')->nullable();
//            $table->double('transport')->nullable();
//            $table->double('transport_tva')->nullable();
//            $table->double('transport_ttc')->nullable();
//            $table->double('magazinage')->nullable();
//            $table->double('magazinage_tva')->nullable();
//            $table->double('magazinage_ttc')->nullable();
//            $table->double('douane_tva')->nullable();
//            $table->double('douane')->nullable();
//            $table->double('douane_ttc')->nullable();
//            $table->double('surestaries')->nullable();
//            $table->double('surestaries_tva')->nullable();
//            $table->double('surestaries_ttc')->nullable();
//            $table->double('manutension')->nullable();
//            $table->double('manutension_tva')->nullable();
//            $table->double('manutension_ttc')->nullable();
//            $table->double('fret')->nullable();
//            $table->double('fret_tva')->nullable();
//            $table->double('fret_ttc')->nullable();
//            $table->double('autres')->nullable();
//            $table->double('autres_tva')->nullable();
//            $table->double('autres_ttc')->nullable();
//            $table->double('cout_revient')->nullable();
//            $table->double('cout_revient_tva')->nullable();
//            $table->double('cout_revient_ttc')->nullable();

           $table->timestamps();

            $table->unsignedBigInteger('provider_id')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();

            $table->foreign('provider_id')
                ->references('id')
                ->on('providers')
                ->onUpdate('cascade');

            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onUpdate('cascade');
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
        Schema::dropIfExists('arrivals');
    }
}
