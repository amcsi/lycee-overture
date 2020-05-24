<?php
declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPreCommentsColumnToCardTranslations extends Migration
{
    public function up()
    {
        Schema::table(
            'card_translations',
            function (Blueprint $table) {
                $table->string('pre_comments')->after('ability_name')->default('');
            }
        );
    }

    public function down()
    {
        Schema::table(
            'card_translations',
            function (Blueprint $table) {
                $table->dropColumn('pre_comments');
            }
        );
    }
}
