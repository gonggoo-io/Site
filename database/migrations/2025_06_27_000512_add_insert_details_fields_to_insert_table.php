<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('insert', function (Blueprint $table) {
            $table->integer('people_count')->nullable()->after('image');
            $table->integer('per_person_count')->nullable()->after('people_count');
            $table->string('address')->nullable()->after('per_person_count');
            
            $table->string('bank')->nullable()->after('address');
            $table->string('account_number')->nullable()->after('bank');
        });
    }

    public function down(): void
    {
        Schema::table('insert', function (Blueprint $table) {
            $table->dropColumn(['people_count', 'per_person_count', 'address', 'bank', 'account_number']);
        });
    }
};
