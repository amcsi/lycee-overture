<?php
declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;

class IncreaseCardsDatePrecisions extends Migration
{
    private const NEW_PRECISION = 6;
    private const OLD_PRECISION = 0;

    public function up()
    {
        $this->changePrecision(self::NEW_PRECISION);
    }

    protected function changePrecision(int $precision): void
    {
        \DB::statement("ALTER TABLE cards MODIFY created_at timestamp($precision) default CURRENT_TIMESTAMP($precision) not null");
        \DB::statement("ALTER TABLE cards MODIFY updated_at timestamp($precision) default CURRENT_TIMESTAMP($precision) not null");
    }

    public function down()
    {
        $this->changePrecision(self::OLD_PRECISION);
    }
}
