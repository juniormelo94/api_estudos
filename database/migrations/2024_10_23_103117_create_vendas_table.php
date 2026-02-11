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
        Schema::create('vendas', function (Blueprint $table) {
            $table->id();
            $table->decimal('preco_total', 10, 2);
            $table->decimal('lucro_total_original', 10, 2);
            $table->decimal('lucro_total_desconto', 10, 2)->nullable();
            $table->string('maquina_cartao')->nullable();
            $table->integer('quantidade_parcelas')->nullable();
            $table->decimal('valor_pacelas', 10, 2)->nullable();
            $table->decimal('taxa_juros', 5, 2)->nullable();
            $table->bigInteger('formas_pagamentos_id')
                  ->unsigned();
            $table->foreign('formas_pagamentos_id')
                  ->references('id')
                  ->on('formas_pagamentos')
                  ->onDelete('cascade');
            $table->bigInteger('clientes_id')
                  ->unsigned();
            $table->foreign('clientes_id')
                  ->references('id')
                  ->on('clientes')
                  ->onDelete('cascade');
            $table->bigInteger('colaboradores_id')
                  ->unsigned();
            $table->foreign('colaboradores_id')
                  ->references('id')
                  ->on('colaboradores')
                  ->onDelete('cascade');
            $table->bigInteger('instalacoes_id')
                  ->unsigned();
            $table->foreign('instalacoes_id')
                  ->references('id')
                  ->on('instalacoes')
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
        Schema::dropIfExists('vendas');
    }
};
