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
        Schema::table('activities', function (Blueprint $table) {
            $table->dropForeign(['id_user']);
            $table->foreign('id_user')->references('id')->on('user_accounts')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('activities', function (Blueprint $table) {
            $table->dropForeign(['id_user']);
            $table->foreign('id_user')->references('id')->on('students')->onDelete('cascade');
        });
    }
};
