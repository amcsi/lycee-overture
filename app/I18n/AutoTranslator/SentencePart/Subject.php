<?php
declare(strict_types=1);

namespace amcsi\LyceeOverture\I18n\AutoTranslator\SentencePart;

use amcsi\LyceeOverture\I18n\AutoTranslator\RegexHelper;
use amcsi\LyceeOverture\I18n\JapaneseCharacterCounter;

/**
 * Subregex for the subject of the sentence.
 */
class Subject
{
    // language=regexp
    private const REGEX = '\{([^}]*)}|(?:((自分の|相手の)?ゴミ箱の|バトル参加|{列1つ}の)?((この|その)キャラと同(列|オーダー)の)?(未行動の|(コスト|EX|DP|AP|SP|DMG)が(\d)点?(以下|以上)?の)?(?:(味方|相手|対象の|対戦|この|その)の?)?((?:[<「].*?[>」]|\[.+?\])*|AF|DF))?(破棄したカード|キャラ|アイテム|イベント|フィールド|[<「].*?[>」]|\{.*})(?:の((?:と?(?:AP|DP|SP|DMG|EX))+))?(が?(\d)[体枚](?:以(上|下))?|全て)?';
    private const REGEX_COMPOUND_AND_OR = '[subject](と|または)[subject]';
    private const REGEX_COMPOUND_ADJACENT = '[subject]に隣接した[subject]';

    private function __construct(
        private string $subjectText,
        /**
         * @var bool Whether the subject is plural.
         */
        private bool $plural
    ) {
    }

    /**
     * @param string $subjectPart String with just the subject.
     */
    public static function createInstance(string $subjectPart): self
    {
        if (preg_match('/^' . self::getCompoundRegexFor(self::REGEX_COMPOUND_AND_OR) . '$/u', $subjectPart, $matches)) {
            $andOr = $matches[2] === 'と' ? 'and' : 'or';
            return new self(
                sprintf(
                    '%s %s%s',
                    self::autoTranslateStrict($matches[1]),
                    $andOr,
                    self::autoTranslateStrict($matches[3])
                ), $andOr === 'and'
            );
        }

        if (preg_match(
            '/^' . self::getCompoundRegexFor(self::REGEX_COMPOUND_ADJACENT) . '$/u',
            $subjectPart,
            $matches
        )) {
            $mainSubject = self::createInstance($matches[2]);
            return new self(
                sprintf(
                    '%s adjacent to%s',
                    $mainSubject->getSubjectText(),
                    self::autoTranslateStrict($matches[1])
                ), $mainSubject->plural()
            );
        }

        // Compound subjects end above here.

        if (!preg_match('/^(?:' . self::REGEX . ')$/u', $subjectPart, $matches)) {
            // This static method expects subject substrings that already match self::getUncapturedRegex().
            throw new \InvalidArgumentException(
                "Subject part does not strictly match regular expression: $subjectPart"
            );
        }

        $something = '';
        $target = next($matches); // The {target} if any.
        $battlingOrGraveyardOrTargetColumn = next($matches); // Graveyard card, or battling character.
        $whosGraveyard = next($matches);

        $sameColumnOrRow = next($matches);
        $whoseColumnOrRow = next($matches);
        $columnOrRowSource = next($matches);

        $adjectiveSource = next($matches); // This "untapped" character / ...with a Cost/DP of n or less.
        $statForRestrictionSource = next($matches); // For stat/property restrictions. E.g. コスト/DP/AP/SP/DMG
        $costAmountSource = next($matches);
        $upToOrUnderSource = next($matches);
        $additionalAdjective = '';

        if ($adjectiveSource) {
            if ($adjectiveSource === '未行動の') {
                // Tapped.

                $something .= ' untapped';
            } else {
                // Cost/stat restriction.

                $nounOfRestriction = $statForRestrictionSource === 'コスト' ? 'cost' : $statForRestrictionSource;

                $adjective = "with a $nounOfRestriction of $costAmountSource";
                if ($upToOrUnderSource) {
                    $upToOrUnder = mb_substr($upToOrUnderSource, -1);
                    if ($upToOrUnder === '下') {
                        $adjective .= ' or less';
                    } elseif ($upToOrUnder === '上') {
                        $adjective .= ' or more';
                    } else {
                        throw new \UnexpectedValueException("Unexpected upToOrUnder: $upToOrUnder");
                    }
                }
                $additionalAdjective = " $adjective";
            }
        }

        $subject = next($matches); // Ally or Enemy in Japanese (or '')
        $typeSource = next($matches); // e.g. [sun] <- characters, "quoted"
        if ($typeSource) {
            $something .= " $typeSource";
        }
        $noun = next($matches);
        $forceNoArticle = false; // Set to true if a/an should definitely not be placed.
        switch ($noun) {
            case 'キャラ':
                $noun = 'character';
                break;
            case 'アイテム':
                $noun = 'item';
                break;
            case 'イベント':
                $noun = 'event';
                break;
            case 'エリア':
                $noun = 'area';
                break;
            case 'フィールド':
                $noun = 'field';
                break;
            case '破棄したカード':
                $noun = 'discarded card';
                $forceNoArticle = true;
                break;
            case false:
                // There is no noun.
                $noun = '';
                break;
            default:
                switch (mb_substr($noun, 0, 1)) {
                    case '{': // Noun is a target (e.g. "{this character's} SP" all being a subject).
                        $forceNoArticle = true;
                        break;
                    case '<':
                    case '「':
                        // Quote.
                        break;
                    default:
                        throw new \LogicException("Unexpected noun: $noun");
                }
                break;
        }

        $itsStatsSource = next($matches); // e.g. のSP

        $allOrHowMany = next($matches);
        $all = $allOrHowMany === '全て';
        $howMany = next($matches);
        $howManyOrMoreLessSource = next($matches);
        $plural = false;
        if (!$target) {
            if ($all) {
                switch ($subject) {
                    case '味方':
                        $text = "all your$something {$noun}s";
                        break;
                    case '相手':
                        $text = "all$something opponent {$noun}s";
                        break;
                    case '':
                        // Unknown
                        $text = "all$something {$noun}s";
                        break;
                    default:
                        throw new \LogicException("Unexpected all subject: $subject");
                }
                $plural = true;
            } else {
                $nounPlural = false;
                switch ($subject) {
                    case '味方':
                        $text = 'ally';
                        if (!$howMany) {
                            $text = 'an ally';
                        }
                        break;
                    case '相手':
                        $text = 'opponent';
                        if (!$howMany) {
                            $text = 'an opponent';
                        }
                        break;
                    case 'この':
                        $text = 'this';
                        break;
                    case 'その':
                        $text = 'that';
                        break;
                    // (target supported)
                    case '対象の':
                        $text = 'that';
                        break;
                    case '対戦':
                        $text = 'opposing';
                        break;
                    case '':
                        // Unknown
                        $text = '';
                        if (!$howMany && !$forceNoArticle) {
                            // Check the $something and the $noun to determine which indefinite article to use.
                            // Default to 'a' by using a fake 'b' as the fallback string.
                            $articleVowelCheck = preg_replace(
                                '/[^\w' . JapaneseCharacterCounter::JAPANESE_LETTERS_RANGES . ']/u',
                                '',
                                $something . $noun . 'b'
                            )[0];

                            $text = preg_match('/[aeiou]/i', $articleVowelCheck) ? 'an' : 'a';
                        }
                        break;
                    default:
                        throw new \LogicException("Unexpected subject: $subject");
                }
                if ($howMany && $howMany !== '1') {
                    $nounPlural = true;
                }
                if ($noun && $nounPlural) {
                    $noun = "{$noun}s";
                }
                if ($howMany) {
                    $howManyOrMore = $howMany;
                    if ($howManyOrMoreLessSource) {
                        $howManyOrMore .= ' ' . ($howManyOrMoreLessSource === '上' ? 'or more' : 'or less');
                    }
                    $text = "$howManyOrMore $text$something {$noun}";
                } else {
                    $text = "$text$something {$noun}";
                }
            }
            $inSomewhere = '';
            if ($battlingOrGraveyardOrTargetColumn) {
                if ($battlingOrGraveyardOrTargetColumn === '{列1つ}の') {
                    $inSomewhere = ' {in 1 column}';
                } elseif ($battlingOrGraveyardOrTargetColumn === 'バトル参加') {
                    $inSomewhere = ' participating in battle';
                } else {
                    switch ($whosGraveyard) {
                        case '自分の':
                            $inSomewhere = ' in your Discard Pile';
                            break;
                        case '相手の':
                            $inSomewhere = " in your opponent's Discard Pile";
                            break;
                        case '':
                            $inSomewhere = ' in the Discard Pile';
                            break;
                        default:
                            throw new \LogicException("Unexpected whosGraveyard: $whosGraveyard");
                    }
                }
            }
            if ($sameColumnOrRow) {
                $whose = $whoseColumnOrRow === 'この' ? 'this' : 'that';
                $columnOrRow = $columnOrRowSource === '列' ? 'column' : 'row';
                $inSomewhere .= " in the same $columnOrRow as $whose character";
            }
            $text = " $text$inSomewhere$additionalAdjective";

            if ($itsStatsSource) {
                // ...'s DP/SP/AP
                $itsStatsText = str_replace('と', ' and ', $itsStatsSource);
                $plural = str_contains($itsStatsText, ' and ');

                $text = self::posessivize(
                        (new Subject((new self($text, $plural))->getSubjectText(), false))
                            ->getSubjectText()
                    ) . " $itsStatsText";
            }
        } else {
            if ($target[-1] === 's') {
                // {Targets} is plural.
                $plural = true;
            }
            $text = '{' . $target . '}';
        }

        return new self($text, $plural);
    }

    public static function autoTranslateStrict(string $subject): string
    {
        return Subject::createInstance($subject)->getSubjectText();
    }

    public static function getUncapturedRegex(): string
    {
        $uncapturedRegex = RegexHelper::uncapture(self::REGEX);

        return sprintf(
            '(?:%s|%s|%s)',
            RegexHelper::uncapture(self::getCompoundRegexFor(self::REGEX_COMPOUND_AND_OR)),
            RegexHelper::uncapture(self::getCompoundRegexFor(self::REGEX_COMPOUND_ADJACENT)),
            $uncapturedRegex
        );

    }

    public function getSubjectText(): string
    {
        return $this->subjectText;
    }

    public function plural(): bool
    {
        return $this->plural;
    }

    /**
     * Makes the passed text into the possessive form.
     */
    public static function posessivize(string $text): string
    {
        if ($text[-1] === '}') {
            // For: {Enemy character} => {Enemy character's}
            return preg_replace_callback(
                '/{(.*)}$/',
                function (array $matches) {
                    return '{' . self::posessivize($matches[1]) . '}';
                },
                $text
            );
        }
        return $text . (
            $text[-1] === 's' ?
                $text . "'" : // Already ends' with an s
                "'s"
            ); // end's.
    }

    /**
     * Gets the regular expression matching a compound subject with and/or.
     * @param string $compoundReplacable A subregex string with multiple [subject]s.
     */
    private static function getCompoundRegexFor(string $compoundReplacable): string
    {
        $subjectRegex = RegexHelper::uncapture(self::REGEX);
        return str_replace('[subject]', "($subjectRegex)", $compoundReplacable);
    }
}
