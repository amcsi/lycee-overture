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

    private $actionTextWithPlaceholders;
    private $demandsPosessiveSubject;
    private $posessivePlural;

    /**
     * @param string $actionTextWithPlaceholders Action text with placeholders for plural
     * @param bool $demandsPosessiveSubject Whether possessive is demanded of the subject, because the action also
     *                                      contains a subject, thus demands for the Subject instance to be posessive.
     *                                      E.g. "(subject's) AP becomes 0", where "AP" is the subject of this action.
     * @param bool|null $posessivePlural If possessive is demanded of the subject, whether the "subject" of this action
     *                                   is plural. For e.g. "(subject's) AP and DP become 0"
     */
    public function __construct(
        string $actionTextWithPlaceholders,
        bool $demandsPosessiveSubject,
        ?bool $posessivePlural
    ) {
        $this->actionTextWithPlaceholders = $actionTextWithPlaceholders;
        $this->demandsPosessiveSubject = $demandsPosessiveSubject;
        $this->posessivePlural = $posessivePlural;
    }

    public function getActionTextWithPlaceholders(): string
    {
        return $this->actionTextWithPlaceholders;
    }

    public function demandsPosessiveSubject(): bool
    {
        return $this->demandsPosessiveSubject;
    }

    public function getPosessivePlural(): ?bool
    {
        return $this->posessivePlural;
    }

    /**
     * Replaces text where the uncaptured Subject is one of the captures, combines the action and subject, and
     * returns the replaced text.
     * The callback should return an Action, and $subjectIndex should point to the index where the subject gets matched.
     *
     * @param string $pattern
     * @param callable $callback
     * @param string $input
     * @param int $subjectIndex
     * @return string
     */
    public static function subjectReplaceCallback(
        string $pattern,
        callable $callback,
        string $input,
        int $subjectIndex = 1
    ): string {
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
