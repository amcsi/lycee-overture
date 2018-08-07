<?php
declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * @see CreateCardsTable
 * @see CardTranslations
 */
class AddTypeToCardTranslations extends Migration
{
    private const CHARACTER_TYPE_FIELD = 'character_type';

    public function up()
    {
        Schema::table('card_translations', function (Blueprint $table): void {
            $table->string(self::CHARACTER_TYPE_FIELD)->default('');
        });
    }

    public function down()
    {
        Schema::table('card_translations', function (Blueprint $table): void {
            $table->dropColumn(self::CHARACTER_TYPE_FIELD);
        });
    }
}
