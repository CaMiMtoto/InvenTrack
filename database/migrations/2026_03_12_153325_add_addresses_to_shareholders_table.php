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
        Schema::table('shareholders', function (Blueprint $table) {
            // Make address relation ids nullable and use null-on-delete to avoid hard constraint failures
            // (you still must ensure existing invalid ids are fixed before the FK is added)
            $table->foreignIdFor(\App\Models\Province::class)->nullable()->constrained()->nullOnDelete();
            $table->foreignIdFor(\App\Models\District::class)->nullable()->constrained()->nullOnDelete();
            $table->foreignIdFor(\App\Models\Sector::class)->nullable()->constrained()->nullOnDelete();
            $table->foreignIdFor(\App\Models\Cell::class)->nullable()->constrained()->nullOnDelete();
            $table->foreignIdFor(\App\Models\Village::class)->nullable()->constrained()->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('shareholders', function (Blueprint $table) {
            $table->dropConstrainedForeignIdFor(\App\Models\Province::class);
            $table->dropConstrainedForeignIdFor(\App\Models\District::class);
            $table->dropConstrainedForeignIdFor(\App\Models\Sector::class);
            $table->dropConstrainedForeignIdFor(\App\Models\Cell::class);
            $table->dropConstrainedForeignIdFor(\App\Models\Village::class);
        });
    }
};
