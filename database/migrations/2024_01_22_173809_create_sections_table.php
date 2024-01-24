<?php

use App\Models\Ligne;
use App\Models\Tarif;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('sections', function (Blueprint $table) {
            $table->id();
            $table->string('Depart');
            $table->string('Arrivee');
            $table->enum('etat', ['actif', 'supprimé'])->default('actif');
            $table->foreignIdFor(Ligne::class)->constrained()->onDelete('cascade');
            $table->foreignIdFor(Tarif::class)->constrained()->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sections');
    }
};
