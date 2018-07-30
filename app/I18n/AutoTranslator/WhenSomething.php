<?php
declare(strict_types=1);

namespace amcsi\LyceeOverture\I18n\AutoTranslator;

use amcsi\LyceeOverture\I18n\AutoTranslator\SentencePart\Action;
use amcsi\LyceeOverture\I18n\AutoTranslator\SentencePart\SentenceCombiner;
use amcsi\LyceeOverture\I18n\AutoTranslator\SentencePart\Subject;

/**
 * For "when something happens" triggers.
 */
class WhenSomething
{
    public static function autoTranslate(string $text): string
    {
        $subjectRegex = Subject::getUncapturedRegex();

        $text = WhenSupporting::autoTranslate($text);
        $text = WhenAppears::autoTranslate($text);
        $text = str_replace('味方キャラがエンゲージ登場したとき', 'when an ally character enters engagement', $text);
        $text = str_replace('行動済みにしたとき', 'when tapped', $text);
        $text = str_replace(
            '自分の効果によって相手キャラを破棄したとき',
            'when an opponent character is destroyed by use of your effects',
            $text
        );
        $text = str_replace(
            'このキャラをエンゲージ登場によって破棄したとき',
            'when this character is destroyed due to Engage summon',
            $text
        );
        $text = str_replace(
            'このキャラで攻撃宣言をしたとき',
            'when you declare an attack with this character',
            $text
        );
        $text = str_replace(
            'このキャラがダウンしたとき',
            'when this character is defeated in battle',
            $text
        );
        $text = str_replace('味方キャラがエンゲージ登場している場合', 'when an ally character gets engaged', $text);
        $text = preg_replace('/\b破棄したとき/u', 'when destroyed', $text);

        // When $subject gets destroyed or moves.
        $text = preg_replace_callback(
            "/($subjectRegex)(を破棄したとき|が移動したとき)/",
            function (array $matches) {
                $subject = Subject::createInstance(next($matches));
                $actionSource = next($matches);
                $thirdPersonPlaceholder = Action::THIRD_PERSON_PLURAL_PLACEHOLDER;

                switch ($actionSource) {
                    case 'を破棄したとき':
                        $actionText = "get$thirdPersonPlaceholder destroyed";
                        break;
                    case 'が移動したとき':
                        $actionText = "move$thirdPersonPlaceholder";
                        break;
                    default:
                        throw new \LogicException("Unexpected actionSource: $actionSource");
                }

                $action = new Action($actionText, false, false);
                return 'when' . SentenceCombiner::combine($subject, $action);
            },
            $text
        );

        return $text;
    }
}
