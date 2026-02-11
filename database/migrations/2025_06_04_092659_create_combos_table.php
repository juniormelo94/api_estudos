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
        Schema::create('combos', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->string('tipo');
            $table->text('descricao');
            $table->string('codigo_barras')->nullable();
            $table->string('qr_code')->nullable();
            $table->longText('img_1')->nullable();
            $table->longText('img_2')->nullable();
            $table->longText('img_3')->nullable();
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
        Schema::dropIfExists('combos');
    }
};
