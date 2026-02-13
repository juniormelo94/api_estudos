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
        Schema::create('questoes', function (Blueprint $table) {
            $table->id();
            $table->longText('texto')->nullable();
            $table->longText('img')->nullable();
            $table->integer('alternativa_correta');
            $table->bigInteger('disciplinas_id')
                  ->unsigned();
            $table->foreign('disciplinas_id')
                  ->references('id')
                  ->on('disciplinas')
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
        Schema::dropIfExists('questoes');
    }
};
