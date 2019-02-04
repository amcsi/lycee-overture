<?php
declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNewsArticlesTable extends Migration
{
    public function up()
    {
        Schema::create('news_articles',
            function (Blueprint $table) {
                $table->increments('id');

                $table->string('title');
                $table->text('markdown');
                $table->text('html');

                $table->timestamp('updated_at')->default(\DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'));
                $table->timestamp('created_at')->default(\DB::raw('CURRENT_TIMESTAMP'));
            });
    }

    public function down()
    {
        Schema::dropIfExists('news_articles');
    }
}
