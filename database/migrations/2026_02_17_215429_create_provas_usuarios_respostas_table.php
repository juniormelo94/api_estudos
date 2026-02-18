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
        Schema::create('provas_usuarios_respostas', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('provas_usuarios_id')
                  ->unsigned();
            $table->foreign('provas_usuarios_id')
                  ->references('id')
                  ->on('provas_usuarios')
                  ->onDelete('cascade');
            $table->bigInteger('questoes_id')
                  ->unsigned();
            $table->foreign('questoes_id')
                  ->references('id')
                  ->on('questoes')
                  ->onDelete('cascade');
            $table->integer('alternativa_marcada')->nullable();
            $table->boolean('acertou')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('provas_usuarios_respostas');
    }
};
