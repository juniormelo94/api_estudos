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
        Schema::create('colaboradores_users', function (Blueprint $table) {
            $table->id();
            $table->string('cargo');
            $table->json('divisoes_ids')->nullable();
            $table->json('instalacoes_ids')->nullable();
            $table->bigInteger('colaboradores_id')
                    ->unsigned();
            $table->foreign('colaboradores_id')
                    ->references('id')
                    ->on('colaboradores')
                    ->onDelete('cascade');
            $table->bigInteger('users_id')
                    ->unsigned();
            $table->foreign('users_id')
                    ->references('id')
                    ->on('users')
                    ->onDelete('cascade');
            $table->bigInteger('tipos_users_id')
                    ->unsigned();
            $table->foreign('tipos_users_id')
                    ->references('id')
                    ->on('tipos_users')
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
        Schema::dropIfExists('colaboradores_users');
    }
};
