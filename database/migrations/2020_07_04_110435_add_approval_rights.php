<?php
declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddApprovalRights extends Migration
{
    public function up()
    {
        Schema::table('users',
            function (Blueprint $table) {
                $table->string('can_approve_locale')->default('');
            });
    }

    public function down()
    {
        Schema::table('users',
            function (Blueprint $table) {
                $table->dropColumn('can_approve_locale');
            });
    }
}
