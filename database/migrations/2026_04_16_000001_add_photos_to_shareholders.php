<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPhotosToShareholders extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('shareholders', function (Blueprint $table) {
            $table->string('photo')->nullable()->after('name');
            $table->string('id_attachment')->nullable()->after('photo');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('shareholders', function (Blueprint $table) {
            $table->dropColumn(['photo', 'id_attachment']);
        });
    }
}

