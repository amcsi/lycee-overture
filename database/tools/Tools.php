<?php
declare(strict_types=1);

namespace Database\Tools;

use Illuminate\Database\Schema\Blueprint;

class Tools
{
    /**
     * Creates creation and update timestamps where the update one auto-updates
     * regardless of the MySQL explicit_defaults_for_timestamp setting.
     */
    public static function timestamps(Blueprint $table, $precision = 0)
    {
        $table->timestamp('updated_at', $precision)
            ->default(\DB::raw("CURRENT_TIMESTAMP($precision) on update CURRENT_TIMESTAMP($precision)"));
        $table->timestamp('created_at', $precision)->default(\DB::raw("CURRENT_TIMESTAMP($precision)"));
    }
}
