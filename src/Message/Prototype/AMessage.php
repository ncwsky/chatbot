<?php

/**
 * This file is part of CommuneChatbot.
 *
 * @link     https://github.com/thirdgerb/chatbot
 * @document https://github.com/thirdgerb/chatbot/blob/master/README.md
 * @contact  <thirdgerb@gmail.com>
 * @license  https://github.com/thirdgerb/chatbot/blob/master/LICENSE
 */

namespace Commune\Message\Prototype;

use Commune\Message\Blueprint\Message;
use Commune\Support\Arr\ArrayAbleToJson;
use Commune\Support\Babel\BabelSerializable;
use Commune\Support\Utils\StringUtils;


/**
 * @author thirdgerb <thirdgerb@gmail.com>
 */
abstract class AMessage implements Message
{
    use ArrayAbleToJson;

    /**
     * 毫秒级的时间戳.
     * @var float
     */
    protected $_createdAt;

    /**
     * 可以做依赖注入的对象名
     * @var string[][]
     */
    private static $_interfaces = [];

    /**
     * AMessage constructor.
     * @param float $_createdAt
     */
    public function __construct(float $_createdAt = null)
    {
        $this->_createdAt = $_createdAt ?? microtime(true);
    }


    /**
     * @param string $input
     * @return static|null
     */
    abstract public static function babelUnSerialize(string $input) : ? BabelSerializable;

    abstract public function babelSerialize(): string;

    abstract public function getData(): array;

    public function toArray(): array
    {
        return [
            'type' => static::getSerializableId(),
            'data' => $this->getData()
        ];
    }

    public static function getSerializableId(): string
    {
        return StringUtils::normalizeClassName(static::class);
    }


    public function getCreatedAt() : float
    {
        return $this->_createdAt
            ?? $this->_createdAt = microtime(true);
    }


    final public function getInterfaces(): array
    {
        $class = static::class;

        if (isset(self::$_interfaces[$class])) {
            return self::$_interfaces[$class];
        }

        $r = new \ReflectionClass($class);

        // 根 message 类名
        $names[] = Message::class;
        // 所有 interface 里继承 message 的.
        foreach ( $r->getInterfaces() as $interfaceReflect ) {
            if ($interfaceReflect->isSubclassOf(Message::class)) {
                $names[] = $interfaceReflect->getName();
            }
        }

        // 抽象父类.
        do  {
            if ($r->isAbstract()) {
                $names[] = $r->getName();
            }


        } while ($r = $r->getParentClass());

        // 当前类名
        $names[] = $r->getName();

        return self::$_interfaces[$class] = $names;
    }

}