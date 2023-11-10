<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::create('movies', function (Blueprint $table) {
            $table->string('imdbID')->primary();
            $table->string('type')->nullable();
            $table->date('released')->nullable();
            $table->integer('year')->nullable();
            $table->string('poster')->nullable();
            $table->string('genre')->nullable();
            $table->string('runtime')->nullable();
            $table->string('country')->nullable();
            $table->float('imdbRating', 3, 1)->nullable();
            $table->integer('imdbVotes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('movies');
    }
};
