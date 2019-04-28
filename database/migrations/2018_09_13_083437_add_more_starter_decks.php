<?php
declare(strict_types=1);

use amcsi\LyceeOverture\Deck;
use Illuminate\Database\Migrations\Migration;

/**
 * Adds more starter decks.
 *
 * Get the list of cards easily by selecting the table in inspect element, then:
 *
 * Array.prototype.map.call($0.querySelectorAll('tr > td:first-child'), node => node.innerText).join(',');
 *
 * @see https://trello.com/c/Dfg5QwFU/31-finish-starter-decks
 */
class AddMoreStarterDecks extends Migration
{
    private const VISUAL_ARTS = 'ビジュアルアーツ 1.0';
    private const FATE_GRAND_ORDER_1_0 = 'Fate/Grand Order 1.0';
    private const BRAVE_SWORD_X_BLADE_SOUL = 'ブレイブソード×ブレイズソウル 1.0';
    private const AUGUST = 'オーガスト 1.0';

    private const SETS = [
        // https://lycee-tcg.com/product/vol09_va/index.html
        [
            'name_ja' => self::VISUAL_ARTS,
            'name_en' => 'Visual Arts 1.0',
            'cards' => 'LO-1293,LO-1294,LO-1295,LO-1296,LO-1171,LO-1172,LO-1173,LO-1175,LO-1178,LO-1183,LO-1187,LO-1188,LO-1189,LO-1190,LO-1276,LO-1277,LO-1278',
            'deck' => true,
        ],
        // https://lycee-tcg.com/product/vol01_fate/index.html
        [
            'name_ja' => self::FATE_GRAND_ORDER_1_0,
            'name_en' => self::FATE_GRAND_ORDER_1_0, // Same.
            'cards' => 'LO-0041,LO-0045,LO-0048,LO-0050,LO-0052,LO-0053,LO-0057,LO-0058,LO-0059,LO-0061,LO-0063,LO-0064,LO-0066,LO-0092,LO-0093,LO-0099,LO-0105,LO-0108,LO-0109,LO-0110,LO-0111',
            'deck' => true,
        ],
        // https://lycee-tcg.com/product/vol02_bravesword_blazesoul/index.html
        [
            'name_ja' => self::BRAVE_SWORD_X_BLADE_SOUL,
            'name_en' => 'Brave Sword X Blaze Soul 1.0',
            'cards' => 'LO-0150,LO-0154,LO-0162,LO-0164,LO-0209,LO-0216,LO-0217,LO-0223,LO-0225,LO-0227,LO-0229,LO-0239,LO-0238,LO-0261,LO-0262,LO-0263,LO-0264',
            'deck' => true,
        ],
        // https://lycee-tcg.com/product/vol07_august/index.html
        [
            'name_ja' => self::AUGUST,
            'name_en' => 'August 1.0',
            'cards' => 'LO-0897,LO-0898,LO-0900,LO-0901,LO-0902,LO-0903,LO-0904,LO-0905,LO-0906,LO-0907,LO-0908,LO-0910,LO-0911,LO-0912,LO-0913,LO-0966,LO-0980,LO-0981,LO-0982,LO-0983',
            'deck' => true,
        ],
    ];

    public function up()
    {
        foreach (self::SETS as $setData) {
            Deck::create($setData);
        }
    }

    public function down()
    {
        app(Deck::class)->whereIn(
            'name_ja',
            array_map(
                function (array $cardSetData) {
                    return $cardSetData['name_ja'];
                },
                self::SETS
            )
        )->delete();
    }
}
