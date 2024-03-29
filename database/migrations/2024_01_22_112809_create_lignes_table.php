<?php

use App\Models\Type;
use App\Models\User;
use App\Models\Reseau;
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
        Schema::create('lignes', function (Blueprint $table) {
            $table->id();
            $table->string('nom');
            $table->enum('etat', ['actif', 'corbeille', 'supprimé'])->default('actif');
            $table->foreignIdFor(Type::class)->nullable()->constrained()->onDelete('cascade');
            $table->foreignIdFor(Reseau::class)->constrained()->onDelete('cascade');
            $table->string('updated_by')->nullable();
            $table->string('created_by')->default("magid@gmail.com");
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
