<?php

/**
 * This file is part of CommuneChatbot.
 *
 * @link     https://github.com/thirdgerb/chatbot
 * @document https://github.com/thirdgerb/chatbot/blob/master/README.md
 * @contact  <thirdgerb@gmail.com>
 * @license  https://github.com/thirdgerb/chatbot/blob/master/LICENSE
 */

namespace Commune\Blueprint\Ghost\Dialogue;

use Commune\Blueprint\Ghost\Dialog;


/**
 * @author thirdgerb <thirdgerb@gmail.com>
 */
interface Receive extends Dialog
{

    public function quit();

}