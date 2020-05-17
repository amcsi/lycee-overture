<?php
declare(strict_types=1);

namespace Tests\Unit\I18n\AutoTranslator\SentencePart\Spanish;

use amcsi\LyceeOverture\I18n\AutoTranslator\SentencePart\Spanish\EsSubject;
use PHPUnit\Framework\TestCase;

final class EsSubjectTest extends TestCase
{
    /**
     * @dataProvider provideCreateInstance
     */
    public function testCreateInstance(string $expected, string $input): void
    {
        self::assertSame($expected, EsSubject::createInstance($input)->getSubjectText());
    }

    public function provideCreateInstance()
    {
        return [
            [
                ' todos tus personajes AF',
                '味方AFキャラ全て',
            ],
            [
                ' ese personaje',
                'そのキャラ',
            ],
            [
                ' 2 personajes aliados y enderzados',
                '未行動の味方キャラ2体',
            ],
            'item (even if it doesnt make sense' => [
                ' todos tus articulos AF',
                '味方AFアイテム全て',
            ],
            'event (even if it doesnt make sense' => [
                ' todos tus eventos AF',
                '味方AFイベント全て',
            ],
            'field as noun' => [
                ' un campo aliado',
                '味方フィールド',
            ],
            'with cost restriction' => [
                ' 1 personaje aliado con costo de 2 o menos',
                'コストが2点以下の味方キャラ1体',
            ],
            'with cost restriction of more' => [
                ' 1 personaje aliado con costo de 2 o más',
                'コストが2点以上の味方キャラ1体',
            ],
            'with exact cost restriction' => [
                ' 1 personaje aliado con costo de 2',
                'コストが2点の味方キャラ1体',
            ],
            'with DP restriction' => [
                ' 1 personaje aliado con DP de 2 o menos',
                'DPが2以下の味方キャラ1体',
            ],
            'with DP restriction exact' => [
                ' 1 personaje con DP de 3',
                'DPが3のキャラ1体',
            ],
            "character's DP" => [
                " el SP de ese personaje",
                'そのキャラのSP',
            ],
            "1 item with sheet (枚) kanji" => [
                ' 1 articulo',
                'アイテム1枚',
            ],
            "1 character in the Discard Pile" => [
                ' 1 personaje en la Pila de Descartes',
                'ゴミ箱のキャラ1体',
            ],
            "1 of your events in the Discard Pile" => [
                ' 1 evento en tu Pila de Descartes',
                '自分のゴミ箱のイベント1枚',
            ],
            "that character in the Discard Pile" => [
                ' ese personaje en la Pila de Descartes',
                'ゴミ箱のそのキャラ',
            ],
            "opponent's Discard Pile" => [
                ' 2 eventos en la Pila de Descartes de tu adversario',
                '相手のゴミ箱のイベント2枚',
            ],
            "this character's SP" => [
                " el SP de este personaje",
                'このキャラのSP',
            ],
            " discarded card's EX" => [
                " el EX de carta descartado",
                '破棄したカードのEX',
            ],
            "this character's SP and AP" => [
                " el SP y AP de este personaje",
                'このキャラのSPとAP',
            ],
            'quoted noun' => [
                ' 1 "稲生滸" en tu Pila de Descartes',
                '自分のゴミ箱の「稲生滸」1体',
            ],
            'gt/lt noun' => [
                ' 1 <稲生滸> en tu Pila de Descartes',
                '自分のゴミ箱の<稲生滸>1体',
            ],
            'quoted noun with another noun' => [
                ' 1 personaje <稲生滸> en tu Pila de Descartes',
                '自分のゴミ箱の<稲生滸>キャラ1体',
            ],
            'quoted 2' => [
                // TODO improve.
                ' 1 personaje aliado y <継続>',
                '味方<継続>キャラ1体',
            ],
            'Discard Pile and cost combination' => [
                ' 1 personaje en tu Pila de Descartes con costo de 2 o menos',
                '自分のゴミ箱のコストが2点以下のキャラ1体',
            ],
            'battling character' => [
                ' 1 personaje participando en batalla',
                'バトル参加キャラ1体',
            ],
            'enemy item' => [
                ' 1 articulo enemigo',
                '相手のアイテム1枚',
            ],
            'same row' => [
                ' 1 personaje aliado en la misma columna de este personaje',
                'このキャラと同列の味方キャラ1体',
            ],
            'same column' => [
                ' 1 personaje aliado en la misma fila de ese personaje',
                'そのキャラと同オーダーの味方キャラ1体',
            ],
            'compound subject' => [
                ' este personaje y ese personaje',
                'このキャラとそのキャラ',
            ],
            'compound subject or' => [
                ' este personaje o ese personaje',
                'このキャラまたはそのキャラ',
            ],
            'compound subject adjacent' => [
                ' 1 personaje adyacente a este personaje',
                'このキャラに隣接したキャラ1体',
            ],
            '4 or more enemy characters' => [
                ' 4 o más personajes enemigos',
                '相手キャラが4体以上',
            ],
            'all your characters in target column' => [
                ' todos personajes {en 1 columna}',
                '{列1つ}のキャラ全て',
            ],
            'correct article' => [
                ' un evento',
                'イベント',
            ],
            'japanese kanji counted for determining article' => [
                ' un evento [雪]',
                '[雪]イベント',
            ],
        ];
    }
}
