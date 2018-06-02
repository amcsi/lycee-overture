<?php

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCardsTable extends Migration
{
    /**
     * Our IDs are custom strings.
     */
    public $incrementing = false;

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cards', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->string('variants')->default('');
            $table->string('rarity')->default('C');
            $table->unsignedTinyInteger('type');
            $table->unsignedTinyInteger('ex')->default(0);

            $table->boolean('snow')->default(0);
            $table->boolean('moon')->default(0);
            $table->boolean('flower')->default(0);
            $table->boolean('space')->default(0);
            $table->boolean('sun')->default(0);

            $table->boolean('cost_star')->default(0);
            $table->boolean('cost_snow')->default(0);
            $table->boolean('cost_moon')->default(0);
            $table->boolean('cost_flower')->default(0);
            $table->boolean('cost_space')->default(0);
            $table->boolean('cost_sun')->default(0);

            $table->unsignedTinyInteger('ap')->default(0);
            $table->unsignedTinyInteger('dp')->default(0);
            $table->unsignedTinyInteger('sp')->default(0);
            $table->unsignedTinyInteger('dmg')->default(0);
            $table->unsignedTinyInteger('ability_type')->default(0);

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
        Schema::dropIfExists('cards');
    }
}
