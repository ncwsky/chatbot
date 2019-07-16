<?php


namespace Commune\Chatbot\App\Components\Predefined\Attitudes;


use Commune\Chatbot\OOHost\Context\Exiting;
use Commune\Chatbot\OOHost\Emotion\Emotions\Positive;
use Commune\Chatbot\App\Intents\MessageIntent;

class AffirmInt extends MessageIntent implements Positive
{
    const SIGNATURE = 'affirm';

    const DESCRIPTION = '确认';

    // 例句都可以用 nlu example manager 进行修改
    const EXAMPLES = [
        '好'
    ];

    public static function getContextName(): string
    {
        return 'attitudes.'.static::SIGNATURE;
    }

    public function __exiting(Exiting $listener): void
    {
    }


}