<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCourierToInsertTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasColumn('insert', 'courier')) {
            Schema::table('insert', function (Blueprint $table) {
                $table->string('courier')->nullable();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('insert', 'courier')) {
            Schema::table('insert', function (Blueprint $table) {
                $table->dropColumn('courier');
            });
        }
    }
}
