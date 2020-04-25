<?php

/**
 * This file is part of CommuneChatbot.
 *
 * @link     https://github.com/thirdgerb/chatbot
 * @document https://github.com/thirdgerb/chatbot/blob/master/README.md
 * @contact  <thirdgerb@gmail.com>
 * @license  https://github.com/thirdgerb/chatbot/blob/master/LICENSE
 */

namespace Commune\Blueprint\Ghost\Exceptions;

use Commune\Blueprint\Exceptions\Runtime\BrokenSessionException;

/**
 * @author thirdgerb <thirdgerb@gmail.com>
 */
class DefNotDefinedException extends BrokenSessionException
{
    public function __construct(string $method, string $defType, string $defName)
    {
        $message = "definition not found, type $defType, name $defName, called by $method";
        parent::__construct($message);
    }
}