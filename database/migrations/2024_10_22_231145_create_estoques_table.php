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
        Schema::create('estoques', function (Blueprint $table) {
            $table->id();
            $table->decimal('desconto_compra', 5, 2)->nullable();
            $table->decimal('preco_compra_original', 10, 2);
            $table->decimal('preco_compra_desconto', 10, 2)->nullable();
            $table->decimal('desconto_venda', 5, 2)->nullable();
            $table->decimal('preco_venda_original', 10, 2);
            $table->decimal('preco_venda_desconto', 10, 2)->nullable();
            $table->decimal('preco_venda_avista', 10, 2)->nullable();
            $table->boolean('vendido')->default(false);
            $table->boolean('desconto_pagamento_avista')->default(true);
            $table->date('vencimento')->nullable();
            $table->bigInteger('produtos_id')
                  ->unsigned();
            $table->foreign('produtos_id')
                  ->references('id')
                  ->on('produtos')
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
        Schema::dropIfExists('estoques');
    }
};
