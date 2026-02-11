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
        Schema::create('combos_produtos', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('combos_id')
                  ->unsigned();
            $table->foreign('combos_id')
                  ->references('id')
                  ->on('combos')
                  ->onDelete('cascade');
            $table->bigInteger('produtos_id')
                  ->unsigned();
            $table->foreign('produtos_id')
                  ->references('id')
                  ->on('produtos')
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
        Schema::dropIfExists('combos_produtos');
    }
};
