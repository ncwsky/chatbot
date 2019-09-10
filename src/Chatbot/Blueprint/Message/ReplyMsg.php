<?php


namespace Commune\Chatbot\Blueprint\Message;


use Illuminate\Support\Collection;

/**
 * information container holds template id and slots
 * when conversation receive view message, will generate real messages with it by render
 */
interface ReplyMsg extends Message
{
    /**
     * 返回 reply 消息的 ID, 用于选择模板.
     *
     * id of the messages template
     * @return string
     */
    public function getId() : string;

    /**
     * Speech Level
     * @return string
     */
    public function getLevel() : string;

    /**
     * variables of the message
     * @return Collection
     */
    public function getSlots() : Collection;
}