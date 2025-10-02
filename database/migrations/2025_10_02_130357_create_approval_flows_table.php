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
        Schema::create('approval_flows', function (Blueprint $table) {
            $table->id();
            $table->morphs('approvable'); // polymorphic relation (Order, Purchase, etc.)
            $table->foreignIdFor(\App\Models\User::class)->constrained(); // who made the action
            $table->string('status');
            $table->text('comment')->nullable(); // optional comment for any action
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('approval_flows');
    }
};
