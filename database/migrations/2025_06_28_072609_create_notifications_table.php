<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->json('data');
            $table->timestamps();
            $table->timestamp('read_at')->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('notifications');
    }
}; 