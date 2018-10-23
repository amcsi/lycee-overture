<?php
declare(strict_types=1);

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Increases ability description character length to 65535 characters.
 */
class IncreaseAbilityDescriptionLength extends Migration
{
    public function up()
    {
        Schema::table('card_translations', function (Blueprint $table) {
            $table->text('ability_description')->change();
        });
    }

    public function down()
    {
        Schema::table('card_translations', function (Blueprint $table) {
            $table->string('ability_description')->change();
        });
    }
}
