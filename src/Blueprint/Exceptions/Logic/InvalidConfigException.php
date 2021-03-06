<?php

/**
 * This file is part of CommuneChatbot.
 *
 * @link     https://github.com/thirdgerb/chatbot
 * @document https://github.com/thirdgerb/chatbot/blob/master/README.md
 * @contact  <thirdgerb@gmail.com>
 * @license  https://github.com/thirdgerb/chatbot/blob/master/LICENSE
 */

namespace Commune\Blueprint\Exceptions\Logic;

use Commune\Blueprint\Exceptions\CommuneLogicException;

/**
 * @author thirdgerb <thirdgerb@gmail.com>
 */
class InvalidConfigException extends CommuneLogicException
{

    public function __construct(string $configType, string $optionName, string $error = '')
    {
        $error = empty($error) ? '' : ", error $error";
        $message = "invalid host config, config type is $configType, option name is $optionName$error";
        parent::__construct($message);
    }

}