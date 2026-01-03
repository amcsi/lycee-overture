<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

return new class extends Migration {
    public function up(): void
    {
        \Schema::table('card_translations', function (Blueprint $table) {
            $table->string('comments', 2000)->default('')->change();
        });
        \Schema::table('suggestions', function (Blueprint $table) {
            $table->string('comments', 2000)->default('')->change();
        });
    }

    public function down(): void
    {
        \Schema::table('card_translations', function (Blueprint $table) {
            $table->string('comments')->default('')->change();
        });
        \Schema::table('suggestions', function (Blueprint $table) {
            $table->string('comments')->default('')->change();
        });
    }
};
