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
        Schema::table('expenses', function (Blueprint $table) {
            $table->integer('qty')->nullable();
            $table->string('unit_measure')->nullable();
            $table->foreignIdFor(\App\Models\Currency::class)->nullable()->constrained();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('expenses', function (Blueprint $table) {
            $table->dropColumn(['qty', 'unit_measure']);
            $table->dropConstrainedForeignIdFor(\App\Models\Currency::class);
        });
    }
};
