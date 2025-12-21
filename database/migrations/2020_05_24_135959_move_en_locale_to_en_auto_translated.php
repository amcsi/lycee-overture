<?php
declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class MoveEnLocaleToEnAutoTranslated extends Migration
{
    private const VALUES = ['en', 'en-auto'];

    public function up()
    {
        $this->update(self::VALUES[0], self::VALUES[1]);
    }

    private function update(string $from, string $to): void
    {
        Schema::table(
            'card_translation',
            function () use ($from, $to) {
                \amcsi\LyceeOverture\Models\CardTranslation::whereLocale($from)->update(['locale' => $to]);
            }
        );
    }

    public function down()
    {
        $this->update(self::VALUES[1], self::VALUES[0]);
    }
}
