<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class AddFirstLastToShareholders extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('shareholders', function (Blueprint $table) {
            // Add nullable first/last name columns to avoid breaking existing rows
            $table->string('first_name')->nullable()->after('name');
            $table->string('last_name')->nullable()->after('first_name');
        });

        // Backfill first_name/last_name from existing name column where possible
        DB::table('shareholders')->select('id', 'name')->orderBy('id')->chunk(100, function ($rows) {
            foreach ($rows as $row) {
                $first = null;
                $last = null;
                if (isset($row->name) && trim($row->name) !== '') {
                    // Split on whitespace: treat last word as last name, rest as first name
                    $parts = preg_split('/\s+/', trim($row->name));
                    if (count($parts) === 1) {
                        $first = $parts[0];
                    } else {
                        $last = array_pop($parts);
                        $first = implode(' ', $parts);
                    }
                }

                DB::table('shareholders')->where('id', $row->id)->update([
                    'first_name' => $first,
                    'last_name' => $last,
                ]);
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
        Schema::table('shareholders', function (Blueprint $table) {
            $table->dropColumn(['first_name', 'last_name']);
        });
    }
}

