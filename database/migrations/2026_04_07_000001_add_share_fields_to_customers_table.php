<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('customers', function (Blueprint $table) {
            if (!Schema::hasColumn('customers', 'first_name')) {
                $table->string('first_name')->nullable()->after('name');
            }
            if (!Schema::hasColumn('customers', 'last_name')) {
                $table->string('last_name')->nullable()->after('first_name');
            }
            if (!Schema::hasColumn('customers', 'id_type')) {
                $table->string('id_type')->nullable()->after('tin');
            }
            if (!Schema::hasColumn('customers', 'date_of_birth')) {
                $table->date('date_of_birth')->nullable()->after('id_type');
            }
            if (!Schema::hasColumn('customers', 'status')) {
                $table->string('status')->nullable()->after('date_of_birth');
            }
            if (!Schema::hasColumn('customers', 'photo_path')) {
                $table->string('photo_path')->nullable()->after('address_photo');
            }
            if (!Schema::hasColumn('customers', 'id_photo_path')) {
                $table->string('id_photo_path')->nullable()->after('photo_path');
            }
            if (!Schema::hasColumn('customers', 'bank_account')) {
                $table->string('bank_account')->nullable()->after('id_photo_path');
            }
            if (!Schema::hasColumn('customers', 'deposit_amount')) {
                $table->decimal('deposit_amount', 15, 2)->default(0)->after('bank_account');
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('customers', function (Blueprint $table) {
            if (Schema::hasColumn('customers', 'deposit_amount')) {
                $table->dropColumn('deposit_amount');
            }
            if (Schema::hasColumn('customers', 'bank_account')) {
                $table->dropColumn('bank_account');
            }
            if (Schema::hasColumn('customers', 'id_photo_path')) {
                $table->dropColumn('id_photo_path');
            }
            if (Schema::hasColumn('customers', 'photo_path')) {
                $table->dropColumn('photo_path');
            }
            if (Schema::hasColumn('customers', 'status')) {
                $table->dropColumn('status');
            }
            if (Schema::hasColumn('customers', 'date_of_birth')) {
                $table->dropColumn('date_of_birth');
            }
            if (Schema::hasColumn('customers', 'id_type')) {
                $table->dropColumn('id_type');
            }
            if (Schema::hasColumn('customers', 'last_name')) {
                $table->dropColumn('last_name');
            }
            if (Schema::hasColumn('customers', 'first_name')) {
                $table->dropColumn('first_name');
            }
        });
    }
};

