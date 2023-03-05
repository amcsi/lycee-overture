<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('sets', function (Blueprint $table) {
            $table->unique(['name_ja', 'version']);
        });
    }

    public function down(): void
    {
        Schema::table('sets', function (Blueprint $table) {
            $table->dropUnique(['name_ja', 'version']);
        });
    }
};
