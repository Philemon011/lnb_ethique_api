<?php

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
        Schema::create('historique_signalements', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('signalement_id')->nullable();
            $table->foreign('signalement_id')
                ->references('id')
                ->on('signalements');

            $table->unsignedBigInteger('user_id')->nullable();
            $table->foreign('user_id')
                ->references('id')
                ->on('users');

            $table->json('modifications'); // Enregistrer les changements sous forme JSON
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('historique_signalements');
    }
};
