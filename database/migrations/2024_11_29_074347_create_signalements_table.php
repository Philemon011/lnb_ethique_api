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
        Schema::create('signalements', function (Blueprint $table) {
            $table->id();
            $table->text('description');
            $table->string('piece_jointe')->nullable(); // Chemin ou lien vers la pièce jointe
            $table->string('code_de_suivi')->unique();

            $table->unsignedBigInteger('type_de_signalement_id');
            $table->unsignedBigInteger('status_id');

            $table->foreign('type_de_signalement_id')
                ->references('id')
                ->on('type_signalements');


            $table->foreign('status_id')
                ->references('id')
                ->on('statuses');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('signalements');
    }
};
