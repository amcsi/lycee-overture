<?php
declare(strict_types=1);

namespace amcsi\LyceeOverture\I18n\AutoTranslator\SentencePart\Spanish;

use amcsi\LyceeOverture\I18n\AutoTranslator\RegexHelper;
use amcsi\LyceeOverture\I18n\AutoTranslator\SentencePart\Subject;

/**
 * Subregex for the subject of the sentence.
 */
class EsSubject
{
    private $subjectText;

    /**
     * @var bool Whether the subject is plural.
     */
    private $plural;

    private function __construct(string $subjectText, bool $plural)
    {
        $this->subjectText = $subjectText;
        $this->plural = $plural;
    }

    /**
     * @param string $subjectPart String with just the subject.
     * @return \amcsi\LyceeOverture\I18n\AutoTranslator\SentencePart\Subject
     */
    public static function createInstance(string $subjectPart): self
    {
        if (preg_match('/^' . static::getCompoundRegexFor(Subject::REGEX_COMPOUND_AND_OR) . '$/u', $subjectPart, $matches)) {
            $andOr = $matches[2] === 'と' ? 'y' : 'o';
            return new self(
                sprintf(
                    '%s %s%s',
                    static::autoTranslateStrict($matches[1]),
                    $andOr,
                    static::autoTranslateStrict($matches[3])
                ), $andOr === 'y'
            );
        }

        if (preg_match(
            '/^' . static::getCompoundRegexFor(Subject::REGEX_COMPOUND_ADJACENT) . '$/u',
            $subjectPart,
            $matches
        )) {
            $mainSubject = static::createInstance($matches[2]);
            return new static(
                sprintf(
                    '%s adyacente a%s',
                    $mainSubject->getSubjectText(),
                    static::autoTranslateStrict($matches[1])
                ), $mainSubject->plural()
            );
        }

        // Compound subjects end above here.

        if (!preg_match('/^(?:' . Subject::REGEX . ')$/u', $subjectPart, $matches)) {
            // This static method expects subject substrings that already match static::getUncapturedRegex().
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

                $something .= ' enderzado';
            } else {
                // Cost/stat restriction.

                $nounOfRestriction = $statForRestrictionSource === 'コスト' ? 'costo' : $statForRestrictionSource;

                $adjective = "con $nounOfRestriction de $costAmountSource";
                if ($upToOrUnderSource) {
                    $upToOrUnder = mb_substr($upToOrUnderSource, -1);
                    if ($upToOrUnder === '下') {
                        $adjective .= ' o menos';
                    } elseif ($upToOrUnder === '上') {
                        $adjective .= ' o más';
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
            $typeSource = static::replaceIfQuoted($typeSource); // If <quoted>.
            $something .= " $typeSource";
        }
        $noun = next($matches);
        $forceNoArticle = false; // Set to true if a/an should definitely not be placed.
        switch ($noun) {
            case 'キャラ':
                $noun = 'personaje';
                break;
            case 'アイテム':
                $noun = 'articulo';
                break;
            case 'イベント':
                $noun = 'evento';
                break;
            case 'フィールド':
                $noun = 'campo';
                break;
            case '破棄したカード':
                $noun = 'carta descartado';
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
                        // Replace Japanese quotes with English ones.
                        $noun = static::replaceIfQuoted($noun);
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
        $text = '';
        $articleOrPronoun = false;
        if (!$target) {
            if ($all) {
                switch ($subject) {
                    case '味方':
                        $text = "todos tus {$noun}s{$something}";
                        break;
                    case '相手':
                        $text = "todos {$noun}s enemigos$something";
                        break;
                    case '':
                        // Unknown
                        $text = "todos {$noun}s$something";
                        break;
                    default:
                        throw new \LogicException("Unexpected all subject: $subject");
                }
                $plural = true;
            } else {
                $nounPlural = false;
                switch ($subject) {
                    case '味方':
                        $text = 'aliado';
                        if (!$howMany) {
                            $articleOrPronoun = true;
                        }
                        break;
                    case '相手':
                        $text = 'enemigo';
                        if (!$howMany) {
                            $articleOrPronoun = true;
                        }
                        break;
                    case 'この':
                        $articleOrPronoun = 'este';
                        break;
                    case 'その':
                        // (target supported)
                    case '対象の':
                        $articleOrPronoun = 'ese';
                        break;
                    case '対戦':
                        $text = 'opuesto';
                        break;
                    case '':
                        // Unknown
                        $text = '';
                        if (!$howMany && !$forceNoArticle) {
                            $articleOrPronoun = true;
                        }
                        break;
                    default:
                        throw new \LogicException("Unexpected subject: $subject");
                }
                if ($howMany && $howMany !== '1') {
                    $nounPlural = true;
                }
                if ($noun && $nounPlural) {
                    $noun = "${noun}s";
                }
                $text = static::assembleText($text, $articleOrPronoun, $something, $noun, $nounPlural);
                if ($howMany) {
                    $howManyOrMore = $howMany;
                    if ($howManyOrMoreLessSource) {
                        $howManyOrMore .= ' ' . ($howManyOrMoreLessSource === '上' ? 'o más' : 'o menos');
                    }
                    $text = "$howManyOrMore $text";
                }
            }
            $inSomewhere = '';
            if ($battlingOrGraveyardOrTargetColumn) {
                if ($battlingOrGraveyardOrTargetColumn === '{列1つ}の') {
                    $inSomewhere = ' {en 1 columna}';
                } elseif ($battlingOrGraveyardOrTargetColumn === 'バトル参加') {
                    $inSomewhere = ' participando en batalla';
                } else {
                    switch ($whosGraveyard) {
                        case '自分の':
                            $inSomewhere = ' en tu Pila de Descartes';
                            break;
                        case '相手の':
                            $inSomewhere = " en la Pila de Descartes de tu adversario";
                            break;
                        case '':
                            $inSomewhere = ' en la Pila de Descartes';
                            break;
                        default:
                            throw new \LogicException("Unexpected whosGraveyard: $whosGraveyard");
                            break;
                    }
                }
            }
            if ($sameColumnOrRow) {
                $whose = $whoseColumnOrRow === 'この' ? 'este' : 'ese';
                $columnOrRow = $columnOrRowSource === '列' ? 'columna' : 'fila';
                $inSomewhere .= " en la misma $columnOrRow de $whose personaje";
            }
            $text = " $text$inSomewhere$additionalAdjective";

            if ($itsStatsSource) {
                // ...'s DP/SP/AP
                $itsStatsText = str_replace('と', ' y ', $itsStatsSource);
                $plural = strpos($itsStatsText, ' y ') !== false;

                $text = static::posessivize(
                    (new self((new self($text, $plural))->getSubjectText(), false))
                        ->getSubjectText()
                    ,
                    $itsStatsText
                );
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
        return static::createInstance($subject)->getSubjectText();
    }

    public static function getUncapturedRegex(): string
    {
        $uncapturedRegex = RegexHelper::uncapture(Subject::REGEX);

        return sprintf(
            '(?:%s|%s|%s)',
            RegexHelper::uncapture(static::getCompoundRegexFor(Subject::REGEX_COMPOUND_AND_OR)),
            RegexHelper::uncapture(static::getCompoundRegexFor(Subject::REGEX_COMPOUND_ADJACENT)),
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
    public static function posessivize(string $text, string $indirectObject): string
    {
        if ($text[-1] === '}') {
            // For: {Enemy character} => {Enemy character's}
            return preg_replace_callback(
                '/{(.*)}$/',
                function (array $matches) use ($indirectObject) {
                    return '{' . static::posessivize($matches[1], $indirectObject) . '}';
                },
                $text
            );
        }

        return " el $indirectObject de$text";
    }

    /**
     * Gets the regular expression matching a compound subject with and/or.
     * @param string $compoundReplacable A subregex string with multiple [subject]s.
     * @return string
     */
    private static function getCompoundRegexFor(string $compoundReplacable): string
    {
        $subjectRegex = RegexHelper::uncapture(Subject::REGEX);
        return str_replace('[subject]', "($subjectRegex)", $compoundReplacable);
    }

    private static function replaceIfQuoted(string $quoted): string
    {
        switch (mb_substr($quoted, 0, 1)) {
            case '<':
                // Leave it as it is.
                break;
            case '「':
                // Replace Japanese quotes with English ones.
                $quoted = str_replace(['「', '」'], '"', $quoted);
                break;
        }
        return $quoted;
    }

    /**
     * @param string $text
     * @param bool|string $articleOrPronoun
     * @param string $something
     * @param string $noun
     * @return string
     */
    private static function assembleText(
        string $text,
        $articleOrPronoun,
        string $something,
        string $noun,
        bool $nounPlural
    ): string
    {
        if ($articleOrPronoun === true) {
            // TODO gender.
            $articleOrPronoun = 'un';
        }

        $adjectives = array_filter(array_map('trim', [$text, $something]));

        if ($nounPlural) {
            $adjectives = array_map(fn($word) => "{$word}s", $adjectives);
        }
        $adjectivesString = implode(' y ', $adjectives);

        return trim(preg_replace('/ {2,}/', ' ', sprintf('%s %s %s', $articleOrPronoun, $noun, $adjectivesString)));
    }
}
