<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('historiques', function (Blueprint $table) {
            $table->id();
            $table->string('Entite');
            $table->integer('ID_Entite');
            $table->integer('id_user');
            $table->string('Operation');
            $table->string('Utilisateur');
            $table->string('reseau_utilisateur');
            $table->string('motif_blockage')->nullable();
            $table->json('Valeur_Avant')->nullable();
            $table->json('Valeur_Apres')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('historiques');
    }
};
