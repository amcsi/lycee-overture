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
        $text = str_replace('味方キャラがエンゲージ登場したとき', 'when an ally character is Engage summoned', $text);
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

                $action = new Action($actionText);
                return 'when' . SentenceCombiner::combine($subject, $action);
            },
            $text
        );

        // When $subject's $stat is $n or more.
        $text = preg_replace_callback(
            "/($subjectRegex)が(\d)(以[上下])?の場合/u",
            function (array $matches) {
                $subject = Subject::createInstance(next($matches));
                $amountSource = next($matches);
                $upToOrUnderSource = next($matches);

                $actionText = "is $amountSource";
                if ($upToOrUnderSource) {
                    if ($upToOrUnderSource) {
                        $upToOrUnder = mb_substr($upToOrUnderSource, -1);
                        if ($upToOrUnder === '下') {
                            $adjective = 'or less';
                        } elseif ($upToOrUnder === '上') {
                            $adjective = 'or more';
                        } else {
                            throw new \UnexpectedValueException("Unexpected upToOrUnder: $upToOrUnder");
                        }
                        $actionText .= " $adjective";
                    }
                }

                $action = new Action($actionText);
                return 'when' . SentenceCombiner::combine($subject, $action);
            },
            $text
        );

        $text = str_replace('このキャラを自分の能力の対象に指定したとき', 'when this character is targeted by one of your effects', $text);
        $text = str_replace('このキャラが自分のデッキから破棄されたとき', 'when this character is discarded from the deck', $text);

        return $text;
    }
}
