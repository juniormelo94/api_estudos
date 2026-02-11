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
        Schema::create('clientes', function (Blueprint $table) {
            $table->id();
            $table->string('nome_completo');
            $table->string('primeiro_nome');
            $table->string('ultimo_nome');
            $table->string('apelido')->nullable();
            $table->string('cpf');
            $table->date('data_nascimento');
            $table->string('rg')->nullable();
            $table->string('sexo');
            $table->string('estado_civil')->nullable();
            $table->longText('img')->nullable();
            $table->string('email_pessoal')->nullable();
            $table->string('telefone_pessoal')->nullable();
            $table->string('celular_pessoal')->nullable();
            $table->string('whatsapp_pessoal')->nullable();
            $table->string('instagram_pessoal')->nullable();
            $table->string('facebook_pessoal')->nullable();
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
        Schema::dropIfExists('clientes');
    }
};
