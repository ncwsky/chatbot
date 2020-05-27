<?php

/**
 * This file is part of CommuneChatbot.
 *
 * @link     https://github.com/thirdgerb/chatbot
 * @document https://github.com/thirdgerb/chatbot/blob/master/README.md
 * @contact  <thirdgerb@gmail.com>
 * @license  https://github.com/thirdgerb/chatbot/blob/master/LICENSE
 */

namespace Commune\Ghost\Stage;

use Commune\Blueprint\Ghost\Dialog;
use Commune\Blueprint\Ghost\Dialog\Activate;
use Commune\Blueprint\Ghost\Dialog\Receive;
use Commune\Blueprint\Ghost\Dialog\Resume;
use Commune\Blueprint\Ghost\Operate\Operator;


/**
 * @author thirdgerb <thirdgerb@gmail.com>
 *
 * @property-read string $name
 * @property-read string $title
 * @property-read string $desc
 * @property-read string $contextName
 * @property-read string $stageName
 * @property-read array $asIntent
 */
class InitStage extends AbsStageDef
{
    public function onActivate(Activate $dialog): Operator
    {
        // 事件
        $next = $this->fireEvent($dialog);
        if (isset($next)) {
            return $next;
        }

        $depending = $dialog->context->dependEntity();

        if (isset($depending)) {
            return $dialog->goStage($depending, $this->getStageShortName());
        }

        $cloner = $dialog->cloner;
        $contextDef = $dialog->ucl->findContextDef($cloner);

        $next = $contextDef->firstStage();
        $task = $dialog->task;

        $ifCancel = $contextDef->onCancelStage();
        $task->onCancel($ifCancel);

        $ifQuit = $contextDef->onQuitStage();
        $task->onQuit($ifQuit);

        // 如果 next 不存在, 直接 fulfill
        return $dialog->next($next);
    }

    public function onReceive(Receive $dialog): Operator
    {
        return $this->fireEvent($dialog)
            ?? $dialog->reactivate();
    }

    public function onRedirect(Dialog $prev, Dialog $current): ? Operator
    {
        return $this->fireRedirect($prev, $current) ?? null;
    }

    public function onResume(Resume $dialog): ? Operator
    {
        return $this->fireEvent($dialog) ?? null;
    }


}