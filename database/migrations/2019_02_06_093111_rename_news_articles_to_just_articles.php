<?php
declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RenameNewsArticlesToJustArticles extends Migration
{
    public function up()
    {
        Schema::table('news_articles',
            function (Blueprint $table) {
                $table->rename('articles');
            });
    }

    public function down()
    {
        Schema::table('articles',
            function (Blueprint $table) {
                $table->rename('news_articles');
            });
    }
}
