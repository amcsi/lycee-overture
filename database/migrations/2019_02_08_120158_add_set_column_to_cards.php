<?php
declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSetColumnToCards extends Migration
{
    public function up()
    {
        Schema::table('cards',
            function (Blueprint $table) {
                $table->unsignedInteger('set_id')->nullable()->after('id');
                $table->foreign('set_id')->references('id')->on('sets')->onDelete('set null');
            });
    }

    public function down()
    {
        Schema::table('cards',
            function (Blueprint $table) {
                $table->dropColumn('set_id');
            });
    }
}
