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
        Schema::create('product_classes', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->float('rate')->unsigned()->default(0);
            $table->timestamps();
        });
        Schema::table('products', function (Blueprint $table) {
            $table->foreignIdFor(\App\Models\ProductClass::class)->nullable()->constrained();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_classes');
        Schema::table('products', function (Blueprint $table) {
            $table->dropConstrainedForeignIdFor(\App\Models\ProductClass::class);
        });
    }
};
