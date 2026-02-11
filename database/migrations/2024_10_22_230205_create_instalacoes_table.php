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
        Schema::create('instalacoes', function (Blueprint $table) {
            $table->id();
            $table->string('endereco');
            $table->string('bairro');
            $table->string('numero');
            $table->string('complemento');
            $table->string('cep');
            $table->string('cidade');
            $table->string('uf');
            $table->string('email')->nullable();
            $table->string('telefone')->nullable();
            $table->string('celular')->nullable();
            $table->string('whatsapp')->nullable();
            $table->string('instagram')->nullable();
            $table->string('facebook')->nullable();
            $table->bigInteger('divisoes_id')
                  ->unsigned();
            $table->foreign('divisoes_id')
                  ->references('id')
                  ->on('divisoes')
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
        Schema::dropIfExists('instalacoes');
    }
};
