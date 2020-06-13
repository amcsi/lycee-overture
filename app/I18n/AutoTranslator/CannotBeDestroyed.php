<?php
declare(strict_types=1);

namespace amcsi\LyceeOverture\I18n\AutoTranslator;

use amcsi\LyceeOverture\I18n\AutoTranslator\SentencePart\Action;
use amcsi\LyceeOverture\I18n\AutoTranslator\SentencePart\Subject;

/**
 * Cannot be destroyed (by battle)
 */
class CannotBeDestroyed
{
    public static function autoTranslate(string $autoTranslated)
    {
        $subjectRegex = Subject::getUncapturedRegex();
        $turnAndBattleRegex = TurnAndBattle::getUncapturedRegex();

        $regex = "/($subjectRegex)は($turnAndBattleRegex)?ダウンしない/u";

        return Action::subjectReplace($regex, [self::class, 'callback'], $autoTranslated);
    }

    public static function callback(array $matches): Action
    {
        $turnAndBattleSource = next($matches);
        $action = 'cannot be downed by battle';
        if ($turnAndBattleSource) {
            $action .= ' ' . TurnAndBattle::autoTranslate($turnAndBattleSource);
        }

        return new Action($action);
    }
}
