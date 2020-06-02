<?php

/**
 * This file is part of CommuneChatbot.
 *
 * @link     https://github.com/thirdgerb/chatbot
 * @document https://github.com/thirdgerb/chatbot/blob/master/README.md
 * @contact  <thirdgerb@gmail.com>
 * @license  https://github.com/thirdgerb/chatbot/blob/master/LICENSE
 */

namespace Commune\Message\Host\QA;

use Commune\Blueprint\Ghost\Cloner;
use Commune\Blueprint\Ghost\Ucl;
use Commune\Protocals\Comprehension;
use Commune\Protocals\HostMsg;
use Commune\Protocals\HostMsg\Convo\QA\AnswerMsg;
use Commune\Support\Message\AbsMessage;
use Commune\Protocals\HostMsg\Convo\QA\QuestionMsg;
use Commune\Protocals\HostMsg\Convo\VerbalMsg;
use Commune\Support\Struct\Struct;
use Commune\Support\Utils\StringUtils;
use Commune\Protocals\HostMsg\Intents\OrdinalInt;

/**
 * @author thirdgerb <thirdgerb@gmail.com>
 *
 *
 * @property-read string $query
 * @property-read string[] $suggestions
 * @property-read string[] $routes
 * @property-read string|null $default
 */
class IQuestionMsg extends AbsMessage implements QuestionMsg
{
    public function __construct(
        string $query,
        string $default = null,
        array $suggestions = [],
        array $routes = []
    )
    {
        parent::__construct([
            'query' => $query,
            'default' => $default,
            'routes' => $routes
        ]);

        foreach ($suggestions as $index => $suggestion) {
            $this->addSuggestion($suggestion, $index);
        }
    }

    public static function stub(): array
    {
        return [
            'query' => '',
            'suggestions' => [],
            'routes' => [],
            'default' => null,
        ];
    }

    public static function create(array $data = []): Struct
    {
        return new static(
            $data['query'] ?? '',
            $data['default'] ?? null,
            $data['suggestions'] ?? [],
            $data['routes'] ?? []
        );
    }

    /*-------- parser --------*/

    public function parse(Cloner $cloner): ? AnswerMsg
    {

        $input = $cloner->input;
        $comprehension = $input->comprehension;
        $answer = $comprehension->answer->getAnswer();

        if (isset($answer)) {
            return $answer;
        }

        $message = $input->getMessage();
        if (!$message instanceof VerbalMsg) {
            return null;
        }

        $answer = $this->isDefault($message)
            ?? $this->parseAnswerByMatcher($cloner)
            ?? $this->isInSuggestions($message)
            ?? $this->acceptAnyAnswer($message)
            ?? null;

        return isset($answer)
            ? $this->setAnswerToComprehension($answer, $comprehension)
            : null;
    }

    protected function setAnswerToComprehension(AnswerMsg $answer, Comprehension $comprehension) : AnswerMsg
    {
        $comprehension->answer->setAnswer($answer);

        $choice = $answer->getChoice();
        $routes = $this->routes;

        if (isset($routes[$choice])) {
            $intent = $routes[$choice];
            $comprehension->intention->setMatchedIntent($intent);
        }

        return $answer;
    }

    protected function acceptAnyAnswer(VerbalMsg $message) : ? AnswerMsg
    {
        return $this->newAnswer($message->getText());
    }

    protected function isInSuggestions(VerbalMsg $message) : ? AnswerMsg
    {
        $matchedIndexes = [];
        $matchedSuggestions = [];

        $text = StringUtils::normalizeString($message->getText());

        foreach ($this->suggestions as $index => $suggestion) {
            $indexStr = StringUtils::normalizeString(strval($index));

            // 完全匹配的情况.
            if ($indexStr === $text) {
                return $this->newAnswer($suggestion, $index);
            }

            // 对索引进行部分匹配.
            if (strstr($indexStr, $text) !== false) {
                $matchedIndexes[] = $index;
            }

            // 对内容进行部分匹配
            $suggestion = StringUtils::normalizeString($suggestion);
            // 如果是其中一部分.
            if (strstr($suggestion, $text) !== false) {
                $matchedSuggestions[] = $index;
            }
        }

        if (count($matchedIndexes) === 1) {
            $index = current($matchedIndexes);
            return $this->newAnswer($this->suggestions[$index], $index);
        }

        if (count($matchedSuggestions) === 1) {
            $index = current($matchedSuggestions);
            return $this->newAnswer($this->suggestions[$index], $index);
        }

        return null;

    }

    protected function isDefault(VerbalMsg $message) : ? AnswerMsg
    {
        if (isset($this->_data['default']) && $message->isEmpty()) {
            $default = $this->default;
            return $this->newAnswer(
                $this->_data['suggestions'][$default] ?? '',
                $default
            );
        }

        return null;
    }

    protected function parseAnswerByMatcher(Cloner $cloner) : ? AnswerMsg
    {
        $matcher = $cloner->matcher->refresh();
        $ordinalInt = HostMsg\IntentMsg::GUEST_DIALOG_ORDINAL;

        if ($matcher->isIntent($ordinalInt)) {
            $entities = $cloner->input
                ->comprehension
                ->intention
                ->getIntentEntities($ordinalInt);

            $index = strval($entities[OrdinalInt::GUEST_DIALOG_ORDINAL][0] ?? 0);

            $suggestions = $this->suggestions;
            if (isset($suggestions[$index])) {
                return $this->newAnswer($suggestions[$index], $index);
            }
        }

        return null;
    }

    protected function newAnswer(string $answer, string $choice = null) : AnswerMsg
    {
        return new IAnswerMsg([
            'answer' => $answer,
            'choice' => $choice
        ]);
    }

    /*-------- methods --------*/

    public function addDefault(string $choice): void
    {
        $this->_data['default'] = $choice;
    }


    public static function relations(): array
    {
        return [];
    }

    public function getQuery(): string
    {
        return $this->query;
    }

    public function getSuggestions(): array
    {
        return $this->suggestions;
    }

    public function addSuggestion(string $suggestion, $index = null, Ucl $ucl = null): void
    {
        if (is_null($index)) {
            $this->_data['suggestions'][] = $suggestion;
        } else {
            $this->_data['suggestions'][$index] = $suggestion;
        }


        if (isset($ucl)) {
            $this->_data['routes'][$index] = $ucl->toEncodedStr();
        }
    }

    public function isEmpty(): bool
    {
        return empty($this->_data['query']);
    }

    public function getRenderId(): string
    {
        return $this->query;
    }

    public function getLevel(): string
    {
        return HostMsg::INFO;
    }

    public function getText(): string
    {
        $text = $this->query . "\n";

        foreach ($this->getSuggestions() as $index => $suggestion) {
            $text .= "[$index] $suggestion \n";
        }

        return $text;
    }


}