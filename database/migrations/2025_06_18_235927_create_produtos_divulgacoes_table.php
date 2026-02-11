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
        Schema::create('produtos_divulgacoes', function (Blueprint $table) {
            $table->id();
            $table->longText('img_padrao_1')->nullable();
            $table->longText('img_padrao_2')->nullable();
            $table->longText('img_padrao_3')->nullable();
            $table->longText('img_promocao_1')->nullable();
            $table->longText('img_promocao_2')->nullable();
            $table->longText('img_promocao_3')->nullable();
            $table->bigInteger('produtos_id')
                  ->unsigned();
            $table->foreign('produtos_id')
                  ->references('id')
                  ->on('produtos')
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
        Schema::dropIfExists('produtos_divulgacoes');
    }
};
