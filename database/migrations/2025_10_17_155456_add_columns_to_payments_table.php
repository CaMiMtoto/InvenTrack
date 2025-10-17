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
        Schema::table('payments', function (Blueprint $table) {
            $table->morphs('paymentable');
            $table->string('status')->change()->default('paid');
            $table->string('attachment')->nullable();
            $table->foreignIdFor(\App\Models\User::class)->nullable()->constrained();
            $table->dropConstrainedForeignIdFor(\App\Models\Order::class);
            $table->text('notes')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {

            $table->string('status')->change()->default('pending');
            $table->dropColumn(['attachment', 'notes']);
            $table->dropConstrainedForeignIdFor(\App\Models\User::class);
            $table->dropMorphs('paymentable');

        });
    }
};
