<?php
declare(strict_types=1);

namespace amcsi\LyceeOverture\I18n\AutoTranslator\SentencePart;

/**
 * Represents an action.
 */
class Action
{
    // Placeholder for plural if the Subjects needs it. "get(s)".
    public const THIRD_PERSON_PLURAL_PLACEHOLDER = '¤thirdPersonPlural¤';

    public function __construct(private string $actionTextWithPlaceholders)
    {
    }

    public function getActionTextWithPlaceholders(): string
    {
        return $this->actionTextWithPlaceholders;
    }

    /**
     * Replaces text where the uncaptured Subject is one of the captures, combines the action and subject, and
     * returns the replaced text.
     * The callback should return an Action, and $subjectIndex should point to the index where the subject gets matched.
     *
     * @param callable|string $callbackOrString If a string, it is used as the Action text string.
     */
    public static function subjectReplace(
        string $pattern,
        callable|string $callbackOrString,
        string $input,
        int $subjectIndex = 1
    ): string {
        if (\is_string($callbackOrString)) {
            $callback = function () use ($callbackOrString): Action {
                return new Action($callbackOrString);
            };
        } else {
            $callback = $callbackOrString;
        }
        $pregReplaceCallback = function ($matches) use ($subjectIndex, $callback): string {
            $subject = Subject::createInstance($matches[$subjectIndex]);
            array_splice($matches, $subjectIndex, 1);
            $action = $callback($matches);
            if (!$action instanceof self) {
                throw new \UnexpectedValueException('callback must return an Action.');
            }
            return SentenceCombiner::combine($subject, $action);
        };

        return preg_replace_callback($pattern, $pregReplaceCallback, $input);
    }
}
