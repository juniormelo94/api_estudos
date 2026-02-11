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
        Schema::create('tipos_users_permissoes', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('tipos_users_id')
                  ->unsigned();
            $table->foreign('tipos_users_id')
                  ->references('id')
                  ->on('tipos_users')
                  ->onDelete('cascade');
            $table->bigInteger('permissoes_id')
                  ->unsigned();
            $table->foreign('permissoes_id')
                  ->references('id')
                  ->on('permissoes')
                  ->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tipos_users_permissoes');
    }
};
