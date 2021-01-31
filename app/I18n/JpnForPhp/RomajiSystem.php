<?php
declare(strict_types=1);

namespace amcsi\LyceeOverture\I18n\JpnForPhp;

use JpnForPhp\Transliterator\System\Hepburn;

/**
 * Override of one of the JpnForPhp transliteration systems.
 *
 * We make sure double vowels are transliterated as 'aa', 'ii', 'uu', 'ee', 'ou' rather than using accents.
 */
class RomajiSystem extends Hepburn implements \Stringable
{
    private $mapping = [
        'あ' => 'a',
        'い' => 'i',
        'う' => 'u',
        'え' => 'e',
        'お' => 'o',
        'か' => 'ka',
        'き' => 'ki',
        'く' => 'ku',
        'け' => 'ke',
        'こ' => 'ko',
        'さ' => 'sa',
        'し' => 'shi',
        'す' => 'su',
        'せ' => 'se',
        'そ' => 'so',
        'た' => 'ta',
        'ち' => 'chi',
        'つ' => 'tsu',
        'て' => 'te',
        'と' => 'to',
        'な' => 'na',
        'に' => 'ni',
        'ぬ' => 'nu',
        'ね' => 'ne',
        'の' => 'no',
        'は' => 'ha',
        'ひ' => 'hi',
        'ふ' => 'fu',
        'へ' => 'he',
        'ほ' => 'ho',
        'ま' => 'ma',
        'み' => 'mi',
        'む' => 'mu',
        'め' => 'me',
        'も' => 'mo',
        'や' => 'ya',
        'ゆ' => 'yu',
        'よ' => 'yo',
        'ら' => 'ra',
        'り' => 'ri',
        'る' => 'ru',
        'れ' => 're',
        'ろ' => 'ro',
        'わ' => 'wa',
        'ゐ' => 'wi',
        'ゑ' => 'we',
        'を' => 'wo',
        'ん' => 'n',
        'が' => 'ga',
        'ぎ' => 'gi',
        'ぐ' => 'gu',
        'げ' => 'ge',
        'ご' => 'go',
        'ざ' => 'za',
        'じ' => 'ji',
        'ず' => 'zu',
        'ぜ' => 'ze',
        'ぞ' => 'zo',
        'だ' => 'da',
        'ぢ' => 'ji',
        'づ' => 'zu',
        'で' => 'de',
        'ど' => 'do',
        'ば' => 'ba',
        'び' => 'bi',
        'ぶ' => 'bu',
        'べ' => 'be',
        'ぼ' => 'bo',
        'ぱ' => 'pa',
        'ぴ' => 'pi',
        'ぷ' => 'pu',
        'ぺ' => 'pe',
        'ぽ' => 'po',
        'ゔ' => 'vu',
        'きゃ' => 'kya',
        'きゅ' => 'kyu',
        'きょ' => 'kyo',
        'しゃ' => 'sha',
        'しゅ' => 'shu',
        'しょ' => 'sho',
        'ちゃ' => 'cha',
        'ちゅ' => 'chu',
        'ちょ' => 'cho',
        'にゃ' => 'nya',
        'にゅ' => 'nyu',
        'にょ' => 'nyo',
        'ひゃ' => 'hya',
        'ひゅ' => 'hyu',
        'ひょ' => 'hyo',
        'みゃ' => 'mya',
        'みゅ' => 'myu',
        'みょ' => 'myo',
        'りゃ' => 'rya',
        'りゅ' => 'ryu',
        'りょ' => 'ryo',
        'ぎゃ' => 'gya',
        'ぎゅ' => 'gyu',
        'ぎょ' => 'gyo',
        'じゃ' => 'ja',
        'じゅ' => 'ju',
        'じょ' => 'jo',
        'ぢゃ' => 'ja',
        'ぢゅ' => 'ju',
        'ぢょ' => 'jo',
        'びゃ' => 'bya',
        'びゅ' => 'byu',
        'びょ' => 'byo',
        'ぴゃ' => 'pya',
        'ぴゅ' => 'pyu',
        'ぴょ' => 'pyo',
        'んあ' => 'n\'a',
        'んい' => 'n\'i',
        'んう' => 'n\'u',
        'んえ' => 'n\'e',
        'んお' => 'n\'o',
        'んや' => 'n\'ya',
        'んゆ' => 'n\'yu',
        'んよ' => 'n\'yo',
        'いぃ' => 'yi',
        'いぇ' => 'ye',
        'うぃ' => 'wi',
        'うぅ' => 'wu',
        'うぇ' => 'we',
        'うぉ' => 'wo',
        'うゃ' => 'wya',
        'ぶぁ' => 'va',
        'ヴぁ' => 'va',
        'ぶぃ' => 'vi',
        'ぶぇ' => 've',
        'ぶぉ' => 'vo',
        'ヴ' => 'vu',
        'ヴぇ' => 've',
        'ぶゃ' => 'vya',
        'ぶゅ' => 'vyu',
        'ぶぃぇ' => 'vye',
        'ぶょ' => 'vyo',
        'きぇ' => 'kye',
        'ぎぇ' => 'gye',
        'くぁ' => 'kwa',
        'くぃ' => 'kwi',
        'くぇ' => 'kwe',
        'くぉ' => 'kwo',
        'ぐぁ' => 'gwa',
        'ぐぃ' => 'gwi',
        'ぐぇ' => 'gwe',
        'ぐぉ' => 'gwo',
        'しぇ' => 'she',
        'じぇ' => 'je',
        'すぃ' => 'si',
        'ずぃ' => 'zi',
        'ちぇ' => 'che',
        'つぁ' => 'tsa',
        'つぃ' => 'tsi',
        'つぇ' => 'tse',
        'つぉ' => 'tso',
        'つゅ' => 'tsyu',
        'てぃ' => 'ti',
        'てぅ' => 'tu',
        'とぅ' => 'tu',
        'てゅ' => 'tyu',
        'でぃ' => 'di',
        'でぅ' => 'du',
        'でゅ' => 'dyu',
        'にぇ' => 'nye',
        'ひぇ' => 'hye',
        'びぇ' => 'bye',
        'ぴぇ' => 'pye',
        'みぇ' => 'mye',
        'りぃ' => 'rii',
        'りぇ' => 'rye',
        'れぇ' => 'rei',
        'ふぁ' => 'fa',
        'ふぃ' => 'fi',
        'ふぇ' => 'fe',
        'ふぉ' => 'fo',
        'ふゃ' => 'fya',
        'ふゅ' => 'fyu',
        'ふぃぇ' => 'fye',
        'ふょ' => 'fyo',
        'ほぅ' => 'hu',
        '　' => ' ',
        '、' => ', ',
        '，' => ', ',
        '：' => ':',
        '・' => '-',
        '。' => '.',
        '！' => '!',
        '？' => '?',
        '‥' => '…',
        '「' => '\'',
        '」' => '\'',
        '『' => '"',
        '』' => '"',
        '（' => '(',
        '）' => ')',
        '｛' => '{',
        '｝' => '}',
        '［' => '[',
        '］' => ']',
        '【' => '[',
        '】' => ']',
        '〜' => '~',
        '〽' => '\'',
    ];

    private $macrons = [
        'a' => 'aa',
        'i' => 'ii',
        'u' => 'uu',
        'e' => 'ee',
        'o' => 'ou',
    ];

    private $longVowels = [
        'aa' => 'aa',
        'uu' => 'uu',
        'ee' => 'ee',
        'oo' => 'ou',
        'ou' => 'ou',
    ];

    private $particles = [
        ' ha ' => ' wa ',
        ' he ' => ' e ',
        ' wo ' => ' o ',
    ];

    /**
     * Override __toString().
     *
     * @see System
     */
    public function __toString(): string
    {
        return 'Hepburn romanization system (ヘボン式ローマ字)';
    }

    /**
     * Override transliterate().
     *
     * @see System
     */
    public function transliterate($str)
    {

        $str = self::preTransliterate($str);

        // Workflow:
        //  1/ Default characters
        //  2/ Sokuon
        //    As per Hepburn system ch > tch
        //    (http://en.wikipedia.org/wiki/Hepburn_romanization#Double_consonants)
        //  3/ Choonpu
        //  4/ Long vowels
        //  5/ Particles

        $str = self::transliterateDefaultCharacters($str, $this->mapping);

        $str = self::transliterateSokuon($str);
        $str = str_replace('cch', 'tch', $str);

        $str = self::transliterateChoonpu($str, $this->macrons);

        $str = self::transliterateLongVowels($str, $this->longVowels);

        $str = self::transliterateParticles($str, $this->particles);

        return self::postTransliterate($str);
    }
}