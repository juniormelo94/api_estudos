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
        Schema::create('alternativas', function (Blueprint $table) {
            $table->id();
            $table->longText('texto')->nullable();
            $table->longText('img')->nullable();
            $table->bigInteger('questoes_id')
                  ->unsigned();
            $table->foreign('questoes_id')
                  ->references('id')
                  ->on('questoes')
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
        Schema::dropIfExists('alternativas');
    }
};
