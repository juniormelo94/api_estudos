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
        Schema::create('maquinas_cartaos', function (Blueprint $table) {
            $table->id();
            $table->string('modelo');
            $table->string('empresa_responsavel');
            $table->json('bandeiras_aceitas');
            $table->decimal('taxa_debito', 5, 2)->nullable();
            $table->json('taxas_parcelas');
            $table->json('taxas_links_parcelas')->nullable();
            $table->decimal('taxa_pix', 5, 2)->nullable();
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
        Schema::dropIfExists('maquinas_cartaos');
    }
};
