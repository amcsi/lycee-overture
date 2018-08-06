<?php
declare(strict_types=1);

namespace amcsi\LyceeOverture\I18n\AutoTranslator\SentencePart;

use amcsi\LyceeOverture\I18n\AutoTranslator\RegexHelper;

/**
 * Subregex for the subject of the sentence.
 */
class Subject
{
    // Placeholder for "'s" if the Action needs it.
    public const POSSESSIVE_PLACEHOLDER = '¤possessive¤';

    // language=regexp
    private const REGEX = '\{([^}]*)}|(?:(未行動の|(コスト|EX|DP|AP|SP|DMG)が(\d)点?(以下|以上)?の)?((自分の|相手の)?ゴミ箱の)?(味方|相手|対象の|対戦|この|その)?((?:[<「].*?[>」]|\[.+?\])*|AF|DF))?(キャラ|アイテム|イベント|フィールド|[<「].*?[>」])(?:の(DMG|AP|DP|SP))?((\d)[体枚]|全て)?';

    private $subjectText;

    /**
     * @var bool Whether the subject is plural.
     */
    private $plural;

    public function __construct(string $subjectText, bool $plural)
    {
        $this->subjectText = $subjectText;
        $this->plural = $plural;
    }

    /**
     * @param string $subjectPart String with just the subject.
     * @return self
     */
    public static function createInstance(string $subjectPart): self
    {
        if (!preg_match('/^(?:' . self::REGEX . ')$/u', $subjectPart, $matches)) {
            // This static method expects subject substrings that already match self::getUncapturedRegex().
            throw new \InvalidArgumentException(
                "Subject part does not strictly match regular expression: $subjectPart"
            );
        }

        $something = '';
        $target = next($matches); // The {target} if any.
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

        $graveyard = next($matches); // Graveyard card.
        $whosGraveyard = next($matches);
        $subject = next($matches); // Ally or Enemy in Japanese (or '')
        $typeSource = next($matches); // e.g. [sun] <- characters, "quoted"
        if ($typeSource) {
            $typeSource = self::replaceIfQuoted($typeSource); // If <quoted>.
            $something .= " $typeSource";
        }
        $noun = next($matches);
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
            case 'フィールド':
                $noun = 'field';
                break;
            case false:
                // There is no noun.
                $noun = '';
                break;
            default:
                switch (mb_substr($noun, 0, 1)) {
                    case '<':
                    case '「':
                        // Replace Japanese quotes with English ones.
                        $noun = self::replaceIfQuoted($noun);
                        break;
                    default:
                        throw new \LogicException("Unexpected noun: $noun");
                }
                break;
        }

        $itsStat = next($matches); // e.g. のSP

        $allOrHowMany = next($matches);
        $all = $allOrHowMany === '全て';
        $howMany = next($matches);
        $plural = false;
        if (!$target) {
            if ($all) {
                switch ($subject) {
                    case '味方':
                        $text = "all your$something {$noun}s";
                        break;
                    case '相手':
                        $text = "all$something enemy {$noun}s";
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
                        $text = 'enemy';
                        if (!$howMany) {
                            $text = 'an enemy';
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
                        if (!$howMany) {
                            $text = preg_match('/[aeiou]/', $noun[0]) ? 'an' : 'a';
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
                if ($howMany) {
                    $text = "$howMany $text$something {$noun}";
                } else {
                    $text = "$text$something {$noun}";
                }
            }
            $inSomewhere = '';
            if ($graveyard) {
                switch ($whosGraveyard) {
                    case '自分の':
                        $inSomewhere = ' in your graveyard';
                        break;
                    case '相手の':
                        $inSomewhere = " in your opponent's graveyard";
                        break;
                    case '':
                        $inSomewhere = ' in the graveyard';
                        break;
                    default:
                        throw new \LogicException("Unexpected whosGraveyard: $whosGraveyard");
                        break;
                }
            }
            $text = " $text$inSomewhere$additionalAdjective" . self::POSSESSIVE_PLACEHOLDER;

            if ($itsStat) {
                // ...'s DP/SP/AP
                $text = (new Subject((new self($text, $plural))->getSubjectTextPosessive(), false))
                        ->getSubjectText() . " $itsStat" . self::POSSESSIVE_PLACEHOLDER;
            }
        } else {
            if ($target[-1] === 's') {
                // {Targets} is plural.
                $plural = true;
            }
            $text = '{' . $target . self::POSSESSIVE_PLACEHOLDER . '}';
        }

        return new self($text, $plural);
    }

    public static function getUncapturedRegex(): string
    {
        return RegexHelper::uncapture(self::REGEX);
    }

    public function getSubjectText(): string
    {
        return $this->subjectText;
    }

    public function getSubjectTextPosessive(): string
    {
        $subjectText = $this->subjectText;
        // E.g. characters => characters'
        $subjectText = str_replace('s' . Subject::POSSESSIVE_PLACEHOLDER, "'", $subjectText);
        // E.g. character => character's
        $subjectText = str_replace(Subject::POSSESSIVE_PLACEHOLDER, "'s", $subjectText);
        return $subjectText;
    }

    public function getSubjectTextWithoutPlaceholders(): string
    {
        return str_replace(self::POSSESSIVE_PLACEHOLDER, '', $this->subjectText);
    }

    public function plural(): bool
    {
        return $this->plural;
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
}
