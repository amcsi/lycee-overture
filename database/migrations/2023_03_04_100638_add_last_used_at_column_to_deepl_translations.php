<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('deepl_translations', function (Blueprint $table) {
            $table->dateTime('last_used_at', 6)->default('1970-01-01 00:00:00.000000');
        });
    }

    public function down(): void
    {
        Schema::table('deepl_translations', function (Blueprint $table) {
            $table->dropColumn('last_used_at');
        });
    }
};
