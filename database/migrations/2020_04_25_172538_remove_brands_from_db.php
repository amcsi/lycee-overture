<?php
declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveBrandsFromDb extends Migration
{
    public function up()
    {
        Schema::table(
            'sets',
            function (Blueprint $table) {
                $table->dropColumn('brand');
            }
        );
    }

    public function down()
    {
        Schema::table(
            'sets',
            function (Blueprint $table) {
                $table->string('brand');
            }
        );
    }
}
