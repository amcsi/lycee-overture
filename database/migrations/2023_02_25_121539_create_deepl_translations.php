<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('deepl_translations', function (Blueprint $table) {
            $table->id();
            $table->text('source');
            $table->text('translation');
            $table->timestamps(6);
        });
    }

    public function down()
    {
        Schema::dropIfExists('deepl_translations');
    }
};
