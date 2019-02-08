<?php
declare(strict_types=1);

use amcsi\LyceeOverture\Set;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSetsTable extends Migration
{
    public function up()
    {
        Schema::create('sets',
            function (Blueprint $table) {
                $table->increments('id');
                $table->string('name_ja');
                $table->string('name_en');
                $table->string('version');
                $table->string('brand');

                $table->timestamp('updated_at')->default(\DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'));
                $table->timestamp('created_at')->default(\DB::raw('CURRENT_TIMESTAMP'));
            });

        $sets = [
            [
                'name_en' => 'Fate/Grand Order',
                'name_ja' => 'Fate/Grand Order',
                'version' => '1.0',
                'brand' => 'FGO',
            ],
            [
                'name_en' => 'Fate/Grand Order',
                'name_ja' => 'Fate/Grand Order',
                'version' => '2.0',
                'brand' => 'FGO',
            ],
            [
                'name_en' => 'Fate/Grand Order',
                'name_ja' => 'Fate/Grand Order',
                'version' => '3.0',
                'brand' => 'FGO',
            ],
            [
                'name_en' => 'Yuzusoft',
                'name_ja' => 'ゆずソフト',
                'version' => '1.0',
                'brand' => 'YUZ',
            ],
            [
                'name_en' => 'August',
                'name_ja' => 'オーガスト',
                'version' => '1.0',
                'brand' => 'AUG',
            ],
            [
                'name_en' => 'Girls & Panzer: Senshadou Daisakusen',
                'name_ja' => 'ガールズ＆パンツァー最終章',
                'version' => '1.0',
                'brand' => 'GUP',
            ],
            [
                'name_en' => 'Girls und Panzer: Destruction Mission!',
                'name_ja' => 'ガールズ＆パンツァー戦車道大作戦！',
                'version' => '1.0',
                'brand' => 'GUP',
            ],
            [
                'name_en' => 'Visual Arts',
                'name_ja' => 'ビジュアルアーツ',
                'version' => '1.0',
                'brand' => 'VA',
            ],
            [
                'name_en' => 'Brave Sword X Blaze Soul',
                'name_ja' => 'ブレイブソード×ブレイズソウル',
                'version' => '1.0',
                'brand' => 'BXB',
            ],
            [
                'name_en' => 'Kamihime Project',
                'name_ja' => '神姫PROJECT',
                'version' => '1.0',
                'brand' => 'KHP',
            ],
        ];

        Set::unguarded(function () use ($sets) {
            Set::insert($sets);
        });
    }

    public function down()
    {
        Schema::dropIfExists('set');
    }
}
