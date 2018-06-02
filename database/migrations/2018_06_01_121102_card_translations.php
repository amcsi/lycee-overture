<?php

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CardTranslations extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('card_translations', function (Blueprint $table) {
            $table->increments('id');
            $table->string('card_id');

            // Translatable fields.
            $table->string('name');
            $table->string('basic_abilities');
            $table->string('ability_name');
            $table->string('ability_cost');
            $table->string('ability_description');
            $table->string('comments');

            $table->string('locale')->index();

            $table->unique(['card_id', 'locale']);
            $table->foreign('card_id')->references('id')->on('cards')->onDelete('cascade');

            $table->timestamp(Model::CREATED_AT)->useCurrent();
            $table->timestamp(Model::UPDATED_AT)->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('card_translations');
    }
}
