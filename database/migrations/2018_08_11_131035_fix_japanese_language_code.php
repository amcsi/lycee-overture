<?php
declare(strict_types=1);

use amcsi\LyceeOverture\Models\CardTranslation;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

/**
 * Fixes all references to 'jp' as a language code to 'ja'.
 */
class FixJapaneseLanguageCode extends Migration
{
    public function up()
    {
        \Illuminate\Support\Facades\Schema::table(
            'card_sets',
            function (Blueprint $table): void {
                $table->renameColumn('name_jp', 'name_ja');
            }
        );
        CardTranslation::where('locale', '=', 'jp')->update(['locale' => 'ja']);
    }

    public function down()
    {
        \Illuminate\Support\Facades\Schema::table(
            'card_sets',
            function (Blueprint $table): void {
                $table->renameColumn('name_ja', 'name_jp');
            }
        );
        CardTranslation::where('locale', '=', 'ja')->update(['locale' => 'jp']);
    }
}
