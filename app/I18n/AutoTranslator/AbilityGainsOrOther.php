<?php
declare(strict_types=1);

namespace amcsi\LyceeOverture\I18n\AutoTranslator;

use amcsi\LyceeOverture\I18n\AutoTranslator\SentencePart\Action;
use amcsi\LyceeOverture\I18n\AutoTranslator\SentencePart\SentenceCombiner;
use amcsi\LyceeOverture\I18n\AutoTranslator\SentencePart\Subject;

/**
 * For characters gaining abilities, or being discarded, or other.
 */
class AbilityGainsOrOther
{
    public static function autoTranslate(string $text): string
    {
        $subjectRegex = Subject::getUncapturedRegex();
        // language=regexp
        $getsSomethingActionRegex = 'は((?:\[.+?\])+)を得る|を(破棄|未行動に|行動済みに)する';

        // "This character gains X."
        $pattern = "/($subjectRegex)($getsSomethingActionRegex)/u";
        $text = preg_replace_callback(
            $pattern,
            ['self', 'callback'],
            $text
        );

        return $text;
    }

    public static function callback(array $matches): string
    {
        $subjectPart = next($matches);
        $subject = Subject::createInstance($subjectPart);

        $action = next($matches);
        $what = next($matches);
        $s = SentencePart\Action::THIRD_PERSON_PLURAL_PLACEHOLDER;
        switch ($action) {
            case 'を破棄する':
                $doesAction = "get$s destroyed";
                break;
            case 'を未行動にする':
                $doesAction = "get$s untapped";
                break;
            case 'を行動済みにする':
                $doesAction = "get$s tapped";
                break;
            default:
                if (isset($what)) {
                    $doesAction = "gain$s $what";
                } else {
                    throw new \InvalidArgumentException("Unexpected action: $action");
                }
        }

        $action = new Action("$doesAction", false, false);

        return SentenceCombiner::combine($subject, $action);
    }
}
