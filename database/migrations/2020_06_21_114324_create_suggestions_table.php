<?php
declare(strict_types=1);

use database\tools\Tools;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSuggestionsTable extends Migration
{
    public function up()
    {
        Schema::create('suggestions',
            function (Blueprint $table) {
                $table->id();

                $table->string('card_id');
                $table->foreign('card_id')->references('id')->on('cards')->onDelete('cascade');

                $table->unsignedInteger('creator_id')->nullable();
                $table->foreign('creator_id')->references('id')->on('users')->onDelete('set null');

                $table->string('locale')->index();

                $table->unique(['card_id', 'locale']);

                $table->string('basic_abilities');
                $table->string('pre_comments');
                $table->string('ability_cost');
                $table->text('ability_description');
                $table->string('comments');

                Tools::timestamps($table, 6);
            });
    }

    public function down()
    {
        Schema::dropIfExists('suggestions');
    }
}
