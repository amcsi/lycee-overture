<?php
declare(strict_types=1);

use amcsi\LyceeOverture\Deck;
use Illuminate\Database\Migrations\Migration;

class AddInitialCardSets extends Migration
{
    private const FATE_GRAND_ORDER_2_0 = 'Fate/Grand Order 2.0';
    private const GIRLS_UND_PANZER = 'ガールズ＆パンツァー 最終章 1.0';
    private const KAMIHIME_PROJECT = '神姫PROJECT 1.0';
    private const YUZU_SOFT = 'ゆずソフト 1.0';

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Because name_jp has since been renamed to name_ja.
        \Eloquent::unguard();

        Deck::create(
            [
                'name_jp' => self::FATE_GRAND_ORDER_2_0,
                'name_en' => 'Fate/Grand Order 2.0',
                'cards' => 'LO-0467,LO-0468,LO-0473,LO-0475,LO-0479,LO-0481,LO-0484,LO-0485,LO-0487,LO-0488,LO-0489,LO-0545,LO-0550,LO-0556,LO-0557,LO-0567,LO-0568,LO-0569,LO-0570',
                'deck' => true,
            ]
        );
        Deck::create(
            [
                'name_jp' => self::GIRLS_UND_PANZER,
                'name_en' => 'Girls und Panzer Senshadou Daisakusen! 1.0',
                'cards' => 'LO-0289,LO-0346,LO-0347,LO-0348,LO-0349,LO-0350,LO-0351,LO-0362,LO-0364,LO-0366,LO-0417,LO-0418,LO-0419,LO-0420,LO-0421',
                'deck' => true,
            ]
        );
        Deck::create(
            [
                'name_jp' => self::KAMIHIME_PROJECT,
                'name_en' => 'Kamihime Project 1.0',
                'cards' => 'LO-0781,LO-0782,LO-0783,LO-0784,LO-0785,LO-0786,LO-0787,LO-0788,LO-0789,LO-0790,LO-0792,LO-0793,LO-0830,LO-0842,LO-0843,LO-0844,LO-0845,LO-0846',
                'deck' => true,
            ]
        );
        Deck::create(
            [
                'name_jp' => self::YUZU_SOFT,
                'name_en' => 'Yuzusoft 1.0',
                'cards' => 'LO-0662,LO-0669,LO-0671,LO-0672,LO-0673,LO-0679,LO-0680,LO-0684,LO-0686,LO-0688,LO-0700,LO-0703,LO-0712,LO-0713,LO-0714,LO-0715',
                'deck' => true,
            ]
        );
    }

    public function down()
    {
        app(Deck::class)->whereIn(
            'name_jp',
            [
                self::FATE_GRAND_ORDER_2_0,
                self::GIRLS_UND_PANZER,
                self::KAMIHIME_PROJECT,
                self::YUZU_SOFT,
            ]
        )->delete();
    }
}
