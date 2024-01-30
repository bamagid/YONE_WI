<?php

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
        Schema::create('tarifs', function (Blueprint $table) {
            $table->id();
            $table->integer('prix');
            $table->string('type');
            $table->enum('etat', ['actif', 'corbeille', 'supprimé'])->default('actif');
            $table->string('updated_by')->nullable();
            $table->string('created_by')->default("magid@gmail.com");
            $table->foreignIdFor(Reseau::class)->constrained()->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tarifs');
    }
};
