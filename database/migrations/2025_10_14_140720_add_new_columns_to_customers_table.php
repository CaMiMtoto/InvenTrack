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
        Schema::table('customers', function (Blueprint $table) {
            $table->string('landmark')->nullable();
            $table->string('nickname')->nullable();
            $table->string('address_photo')->nullable();
            $table->string('id_number')->nullable();
//            record GPS coordinate of customer
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->foreignIdFor(\App\Models\District::class)->nullable()->constrained();
            $table->foreignIdFor(\App\Models\Sector::class)->nullable()->constrained();
            $table->foreignIdFor(\App\Models\Cell::class)->nullable()->constrained();
            $table->foreignIdFor(\App\Models\Village::class)->nullable()->constrained();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            //
        });
    }
};
