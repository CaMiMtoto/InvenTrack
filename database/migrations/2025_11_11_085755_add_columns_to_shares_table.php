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
        Schema::table('shares', function (Blueprint $table) {
            $table->foreignIdFor(\App\Models\User::class)->nullable()->constrained();
            $table->foreignIdFor(\App\Models\User::class, 'reviewed_by')->nullable()->constrained();
            $table->dateTime('reviewed_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('shares', function (Blueprint $table) {
            $table->dropConstrainedForeignIdFor(\App\Models\User::class);
            $table->dropConstrainedForeignIdFor(\App\Models\User::class, 'reviewed_by');
            $table->dropColumn('reviewed_at');
        });
    }
};
