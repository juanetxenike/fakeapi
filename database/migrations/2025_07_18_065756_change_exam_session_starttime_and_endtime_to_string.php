<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('examsessions', function (Blueprint $table) {
            // We are changing the starttime and endtime columns to string type
            if (Schema::hasColumn('examsessions', 'starttime')) {
                $table->string('starttime')->change();
            }
            if (Schema::hasColumn('examsessions', 'endtime')) {
                $table->string('endtime')->change();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('examsessions', function (Blueprint $table) {
            if (Schema::hasColumn('examsessions', 'starttime')) {
                $table->time('starttime')->change();
            }
            if (Schema::hasColumn('examsessions', 'endtime')) {
                $table->time('endtime')->change();
            }
        });
    }
};
