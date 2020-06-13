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
        $text = str_replace('味方キャラがエンゲージ登場したとき', 'if an allied character has been Engage Summoned', $text);
        $text = str_replace('行動済みにしたとき', 'when tapped', $text);
        $text = str_replace(
            '自分の効果によって相手キャラを破棄したとき',
            'when an opponent character is discarded by use of your effects',
            $text
        );
        $text = str_replace(
            'このキャラをエンゲージ登場によって破棄したとき',
            'when this character is discarded due to Engage summon',
            $text
        );
        $text = str_replace(
            'このキャラで攻撃宣言をしたとき',
            'when you declare an attack with this character',
            $text
        );
        $text = Action::subjectReplace(
            "/($subjectRegex)がダウン(?:したとき|していた場合)/u",
            'when [subject] is defeated in battle',
            $text
        );
        $text = str_replace('味方キャラがエンゲージ登場している場合', 'when an ally character gets engaged', $text);
        $text = preg_replace('/\b破棄したとき/u', 'when discarded', $text);
        $text = preg_replace('/\b除外したとき/u', 'when removed from play', $text);

        // When $subject gets destroyed or moves.
        $text = preg_replace_callback(
            "/((自分|相手)の効果で)?($subjectRegex)(を破棄したとき|が移動したとき)/u",
            function (array $matches) {
                $byEffect = next($matches);
                $byYourEffect = next($matches) === '自分';
                $subject = Subject::createInstance(next($matches));
                $actionSource = next($matches);
                $thirdPersonPlaceholder = Action::THIRD_PERSON_PLURAL_PLACEHOLDER;

                switch ($actionSource) {
                    case 'を破棄したとき':
                        $actionText = "get$thirdPersonPlaceholder discarded";
                        break;
                    case 'が移動したとき':
                        $actionText = "move$thirdPersonPlaceholder";
                        break;
                    default:
                        throw new \LogicException("Unexpected actionSource: $actionSource");
                }

                $action = new Action($actionText);
                $return = 'when' . SentenceCombiner::combine($subject, $action);
                if ($byEffect) {
                    $bySubject = $byYourEffect ? 'your' : "your opponent's";
                    $return .= " by $bySubject effect";
                }
                return $return;
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
        $text = str_replace('味方キャラがアイテムを装備している場合', 'when an ally character is equipped with an item', $text);
        $text = str_replace('自分のデッキを破棄したとき', 'when you discard from your deck', $text);
        $text = str_replace(
            'このキャラが相手のデッキにダメージを与えたとき',
            "when this character inflicts damage to your opponent's deck",
            $text
        );
        $text = str_replace(
            '相手キャラの数より味方キャラの数が少ない場合',
            'while you have fewer characters on the field than your opponent',
            $text
        );
        $text = str_replace(
            'このキャラがエンゲージ登場以外で登場したとき',
            'when this character is summoned except by Engage summon',
            $text
        );
        $text = preg_replace(
            '/除外された自分のキャラが(\d+)体以上の場合/u',
            'when there are $1 or more of your characters removed from play',
            $text
        );
        $text = Action::subjectReplace(
            "/($subjectRegex)がアイテムを装備(したとき|している場合)/u",
            function (array $matches): Action {
                $whenOrWhile = $matches[1] === 'したとき' ? 'when' : 'while';
                return new Action("$whenOrWhile [subject] is equipped with an item");
            },
            $text
        );
        $text = Action::subjectReplace(
            "/($subjectRegex)の場合/u",
            function (): Action {
                return new Action("when there are [subject] on the field");
            },
            $text
        );

        return trim($text);
    }
}
