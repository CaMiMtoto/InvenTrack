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
        Schema::table('users', function (Blueprint $table) {
            $table->string('phone_number')->after('email')->nullable();
            $table->boolean('is_super_admin')->default(false)->after('email');
            $table->enum('status', ['active', 'inactive', 'suspended'])->default('active')->after('is_super_admin');
            $table->dateTime('password_changed_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('phone_number');
            $table->dropColumn('is_super_admin');
            $table->dropColumn('password_changed_at');
        });
    }
};
