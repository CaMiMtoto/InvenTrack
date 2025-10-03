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
        Schema::create('images', function (Blueprint $table) {
            $table->id();
            $table->string('path'); // relative storage path
            $table->string('alt_text')->nullable(); // optional alt text for accessibility
            $table->unsignedBigInteger('imageable_id')->nullable(); // model ID
            $table->string('imageable_type')->nullable(); // model class
            $table->boolean('is_primary')->default(false);
            $table->timestamps();
            $table->index(['imageable_id', 'imageable_type']);

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('images');
    }
};
