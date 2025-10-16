<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('flow_histories', function (Blueprint $table) {
            $table->dropColumn(['action']);
            $table->string('status')->nullable();
            $table->boolean('is_comment')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('flow_histories', function (Blueprint $table) {
            $table->string('action');
            $table->dropColumn(['status', 'is_comment']);
        });
    }
};
