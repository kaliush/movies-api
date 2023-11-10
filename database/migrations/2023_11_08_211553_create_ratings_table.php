<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::create('ratings', function (Blueprint $table) {
            $table->uuid()->primary();
            $table->string('movie_imdbID');
            $table->string('source');
            $table->string('value');
            $table->timestamps();

            $table->foreign('movie_imdbID')->references('imdbID')->on('movies')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ratings');
    }
};
