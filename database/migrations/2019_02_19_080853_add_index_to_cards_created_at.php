<?php
declare(strict_types=1);

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIndexToCardsCreatedAt extends Migration
{
    public function up()
    {
        Schema::table('cards', function (Blueprint $table) {
            $table->index('created_at');
        });
    }

    public function down()
    {
        Schema::table('cards', function (Blueprint $table) {
            $table->dropIndex('cards_created_at_index');
        });
    }
}
