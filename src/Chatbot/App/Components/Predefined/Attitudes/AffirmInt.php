<?php


namespace Commune\Chatbot\App\Components\Predefined\Attitudes;


use Commune\Chatbot\OOHost\Emotion\Emotions\Positive;

class AffirmInt extends AttitudeInt implements Positive
{
    const SIGNATURE = 'affirm';

    const DESCRIPTION = '确认';

}