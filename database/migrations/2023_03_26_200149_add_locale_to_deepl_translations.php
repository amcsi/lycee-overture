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
        Schema::table('deepl_translations', function (Blueprint $table) {
            $table->string('locale')->default(\amcsi\LyceeOverture\I18n\Locale::ENGLISH);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('deepl_translations', function (Blueprint $table) {
            $table->dropColumn('locale');
        });
    }
};
