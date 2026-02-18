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
        Schema::create('provas_usuarios', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('provas_id')
                  ->unsigned();
            $table->foreign('provas_id')
                  ->references('id')
                  ->on('provas')
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
        Schema::dropIfExists('provas_usuarios');
    }
};
