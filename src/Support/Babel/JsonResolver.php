<?php

/**
 * This file is part of CommuneChatbot.
 *
 * @link     https://github.com/thirdgerb/chatbot
 * @document https://github.com/thirdgerb/chatbot/blob/master/README.md
 * @contact  <thirdgerb@gmail.com>
 * @license  https://github.com/thirdgerb/chatbot/blob/master/LICENSE
 */

namespace Commune\Support\Babel;
use Commune\Support\Utils\StringUtils;


/**
 * @author thirdgerb <thirdgerb@gmail.com>
 */
class JsonResolver implements BabelResolver
{
    protected $transformers = [];

    public function registerSerializable(string $serializable) : void
    {
        if (!is_a(
            $serializable,
            BabelSerializable::class,
            TRUE
        )) {
            throw new \InvalidArgumentException("$serializable is not instance of " . BabelSerializable::class);
        }

        /**
         * @var BabelSerializable $serializable
         */

        static::register(
            call_user_func([$serializable, 'getSerializableId']),
            function(BabelSerializable $obj) {
                return $obj->toSerializableArray();
            },
            [$serializable, 'fromSerializableArray']
        );
    }

    /**
     * 注册一个 resolver
     *
     * @param string $serializableId
     * @param callable $serializer
     * @param callable $unSerializer
     */
    public function register(
        string $serializableId,
        callable $serializer,
        callable $unSerializer
    ) : void
    {
        $this->transformers[$serializableId] = [$serializer, $unSerializer];
    }

    public function hasRegistered(
        string $serializableId
    ) : bool
    {
        return array_key_exists($serializableId, $this->transformers);
    }

    public function toSerializingArray(BabelSerializable $serializable): array
    {
        return [
            $serializable->getSerializableId(),
            $serializable->toSerializableArray()
        ];
    }

    public function fromSerializableArray(array $data): ? BabelSerializable
    {
        $serializableId = $data[0];
        $serialized = $data[1];

        if ($this->hasRegistered($serializableId)) {
            $unSerializer = $this->transformers[$serializableId][1];
            return call_user_func($unSerializer, $serialized);
        }


        $serializableId = StringUtils::dotToNamespaceSlash($serializableId);

        if (is_a($serializableId, BabelSerializable::class, TRUE)
        ) {
            $this->registerSerializable($serializableId);
            return call_user_func([$serializableId, 'fromSerializableArray'], $serialized);
        }

        return null;
    }


    /**
     * 序列化, 通常有一个加密环节.
     * @param BabelSerializable $serializable
     * @return string
     */
    public function serialize($serializable) : string
    {
        if ($serializable instanceof BabelSerializable) {
            $id = $serializable->getSerializableId();

            if (!static::hasRegistered($id)) {
                static::registerSerializable(get_class($serializable));
            }

            $data = $this->toSerializingArray($serializable);
            return json_encode(
                $data,
                JSON_UNESCAPED_UNICODE
            );
        }

        return serialize($serializable);
    }

    /**
     * 反序列化. 通常还有一个解密环节.
     * @param string $input
     * @return null|mixed 如果为 null, 表示无法反序列化.
     */
    public function unSerialize(string $input)
    {
        $data = json_decode($input, true);

        if (
            is_array($data)
            && count($data) === 2
            && isset($data[0])
            && isset($data[1])
        ) {
            return $this->fromSerializableArray($data);
        }
        return unserialize($input);
    }


}