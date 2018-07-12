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
    private const REGEX = '\{([^}]*)}|(?:(味方|相手|この|対象の|対戦)((?:\[.+?\])*))?キャラ((\d)体|全て)?';

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
        preg_match('/^' . self::REGEX . '$/', $subjectPart, $matches);
        $target = next($matches); // The {target} if any.
        $subject = next($matches); // Ally or Enemy in Japanese (or '')
        $something = next($matches); // e.g. [sun] <- characters
        $allOrHowMany = next($matches);
        $all = $allOrHowMany === '全て';
        $howMany = next($matches);
        $plural = false;
        if (!$target) {
            if ($all) {
                switch ($subject) {
                    case '味方':
                        $text = "all your $something characters";
                        break;
                    case '相手':
                        $text = "all enemy $something characters";
                        break;
                    case '':
                        // Unknown
                        $text = "all $something characters";
                        break;
                    default:
                        throw new \LogicException("Unexpected all subject: $subject");
                }
                $plural = true;
            } else {
                switch ($subject) {
                    case '味方':
                        $text = 'ally';
                        break;
                    case '相手':
                        $text = 'enemy';
                        break;
                    case 'この':
                        $text = 'this';
                        break;
                    case '対象の':
                        $text = 'that';
                        break;
                    case '対戦':
                        $text = 'battling opponent\'s';
                        break;
                    case '':
                        // Unknown
                        $text = '';
                        break;
                    default:
                        throw new \LogicException("Unexpected subject: $subject");
                }
                $text = "$text $something character";
                if ($howMany > 1) {
                    $plural = true;
                    $text = "$howMany ${text}s";
                }
            }
            $text = " $text" . self::POSSESSIVE_PLACEHOLDER;
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

    public function plural(): bool
    {
        return $this->plural;
    }
}
