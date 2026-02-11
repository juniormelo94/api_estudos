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
        Schema::create('vendas_estoques', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('vendas_id')
                  ->unsigned();
            $table->foreign('vendas_id')
                  ->references('id')
                  ->on('vendas')
                  ->onDelete('cascade');
            $table->bigInteger('estoques_id')
                  ->unsigned();
            $table->foreign('estoques_id')
                  ->references('id')
                  ->on('estoques')
                  ->onDelete('cascade');
            $table->string('status');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vendas_estoques');
    }
};
