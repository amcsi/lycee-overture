<?php
declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RenameCardSetsToDecks extends Migration
{
    public function up()
    {
        Schema::table('card_sets', function (Blueprint $table) {
            $table->dropColumn('deck');
            $table->rename('decks');
        });
    }

    public function down()
    {
        Schema::table('decks', function (Blueprint $table) {
            $table->boolean('deck')->default(true)->after('cards');
            $table->rename('card_sets');
        });
    }
}
