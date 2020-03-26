<?php

/**
 * This file is part of CommuneChatbot.
 *
 * @link     https://github.com/thirdgerb/chatbot
 * @document https://github.com/thirdgerb/chatbot/blob/master/README.md
 * @contact  <thirdgerb@gmail.com>
 * @license  https://github.com/thirdgerb/chatbot/blob/master/LICENSE
 */

namespace Commune\Chatbot\Blueprint;

use Commune\Ghost\Blueprint\GhostConfig;
use Commune\Shell\Blueprint\ShellConfig;
use Commune\Support\Struct\Structure;

/**
 * @author thirdgerb <thirdgerb@gmail.com>
 *
 * @property-read string $chatbotName
 * @property-read array $providers
 * @property-read GhostConfig $ghost
 * @property-read ShellConfig[] $shells
 */
class ChatbotConfig extends Structure
{
    const IDENTITY = 'chatbotName';


    public static function stub(): array
    {
        return [
            'chatbotName' => '',

        ];
    }


}