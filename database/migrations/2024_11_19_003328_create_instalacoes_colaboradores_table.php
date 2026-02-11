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
        Schema::create('instalacoes_colaboradores', function (Blueprint $table) {
            $table->id();
            $table->longText('observacoes')->nullable();
            $table->bigInteger('instalacoes_id')
                  ->unsigned();
            $table->foreign('instalacoes_id')
                  ->references('id')
                  ->on('instalacoes')
                  ->onDelete('cascade');
            $table->bigInteger('colaboradores_id')
                  ->unsigned();
            $table->foreign('colaboradores_id')
                  ->references('id')
                  ->on('colaboradores')
                  ->onDelete('cascade');
            $table->string('criado_por');
            $table->string('atualizado_por')->nullable();
            $table->string('status');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('instalacoes_colaboradores');
    }
};
