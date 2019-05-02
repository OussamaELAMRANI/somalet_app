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
            $table->string('n_facture')->unique();
            $table->float('price_devise')->nullable();
            $table->float('cours_change')->nullable();
            $table->float('taux_marge')->nullable();
            $table->date('date_facture')->nullable();
            $table->enum('type',['INTERNE','NATIONAL','INTERNATIONAL'])->default('INTERNATIONAL');
            $table->float('price_provider')->nullable(); //en dh
            $table->float('transitaire')->nullable();
            $table->float('transitaire_tva')->nullable();
            $table->float('transitaire_ttc')->nullable();
            $table->float('transport')->nullable();
            $table->float('transport_tva')->nullable();
            $table->float('transport_ttc')->nullable();
            $table->float('magazinage')->nullable();
            $table->float('magazinage_tva')->nullable();
            $table->float('magazinage_ttc')->nullable();
            $table->float('surestaries')->nullable();
            $table->float('surestaries_tva')->nullable();
            $table->float('surestaries_ttc')->nullable();
            $table->float('manutension')->nullable();
            $table->float('manutension_tva')->nullable();
            $table->float('manutension_ttc')->nullable();
            $table->float('fret')->nullable();
            $table->float('fret_tva')->nullable();
            $table->float('fret_ttc')->nullable();
            $table->float('autres')->nullable();
            $table->float('autres_tva')->nullable();
            $table->float('autres_ttc')->nullable();
            $table->float('cout_revient')->nullable();
            $table->float('cout_revient_tva')->nullable();
            $table->float('cout_revient_ttc')->nullable();
            $table->timestamps();

            $table->unsignedBigInteger('provider_id');
            $table->unsignedBigInteger('user_id');

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
