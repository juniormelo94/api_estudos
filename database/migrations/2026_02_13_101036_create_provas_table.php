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
        Schema::create('provas', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->bigInteger('modelos_provas_id')
                  ->unsigned();
            $table->foreign('modelos_provas_id')
                  ->references('id')
                  ->on('modelos_provas')
                  ->onDelete('cascade');
           $table->string('status');
            $table->integer('criado_por');
            $table->integer('atualizado_por')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('provas');
    }
};
