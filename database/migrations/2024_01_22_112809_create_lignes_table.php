<?php

use App\Models\Reseau;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('lignes', function (Blueprint $table) {
            $table->id();
            $table->string('nom');
            $table->string('type');
            $table->enum('etat', ['actif', 'supprimé'])->default('actif');
            $table->foreignIdFor(Reseau::class)->constrained()->onDelete('cascade');
            $table->string('lieuDepart');
            $table->string('lieuArrivee');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lignes');
    }
};