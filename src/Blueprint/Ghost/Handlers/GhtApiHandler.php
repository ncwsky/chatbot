<?php

/**
 * This file is part of CommuneChatbot.
 *
 * @link     https://github.com/thirdgerb/chatbot
 * @document https://github.com/thirdgerb/chatbot/blob/master/README.md
 * @contact  <thirdgerb@gmail.com>
 * @license  https://github.com/thirdgerb/chatbot/blob/master/LICENSE
 */

namespace Commune\Blueprint\Ghost\Handlers;

use Commune\Blueprint\Ghost\Request\GhostRequest;
use Commune\Blueprint\Ghost\Request\GhostResponse;
use Commune\Protocals\HostMsg\Convo\ApiMsg;

/**
 * @author thirdgerb <thirdgerb@gmail.com>
 */
interface GhtApiHandler
{

    public function __invoke(GhostRequest $request, ApiMsg $message) : GhostResponse;
}