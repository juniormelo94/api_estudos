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
        Schema::create('calendarios_estudos', function (Blueprint $table) {
            $table->id();
            $table->string('descricao');
            $table->integer('dia_semana');
            $table->timestamp('tempo_estudo')->nullable();
            $table->timestamp('inicio_estudo')->nullable();
            $table->timestamp('fim_estudo')->nullable();
            $table->integer('ordem_estudo');
            $table->boolean('apostilas_estudo')->default(false);
            $table->boolean('videos_estudo')->default(false);
            $table->boolean('audios_estudo')->default(false);
            $table->boolean('questoes_estudo')->default(false);
            $table->boolean('simulados_estudo')->default(false);
            $table->boolean('provas_estudo')->default(false);
            $table->bigInteger('planos_estudos_id')
                  ->unsigned();
            $table->foreign('planos_estudos_id')
                  ->references('id')
                  ->on('planos_estudos')
                  ->onDelete('cascade');
            $table->integer('disciplinas_id')->nullable();
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
        Schema::dropIfExists('calendarios_estudos');
    }
};
