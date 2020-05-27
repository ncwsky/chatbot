<?php

/**
 * This file is part of CommuneChatbot.
 *
 * @link     https://github.com/thirdgerb/chatbot
 * @document https://github.com/thirdgerb/chatbot/blob/master/README.md
 * @contact  <thirdgerb@gmail.com>
 * @license  https://github.com/thirdgerb/chatbot/blob/master/LICENSE
 */

namespace Commune\Blueprint\Ghost\MindDef;

/**
 * @author thirdgerb <thirdgerb@gmail.com>
 */
interface ParamDefCollection
{
    /**
     * @return string[]
     */
    public function getParamNames() : array;

    /**
     * @param string $name
     * @return bool
     */
    public function hasParam(string $name) : bool;

    /**
     * @param string $name
     * @return ParamDef
     */
    public function getParam(string $name) : ParamDef;

    /**
     * @return ParamDef[]
     *  [name => DefParam]
     */
    public function getAllParams() : array;

    /**
     * @return int
     */
    public function countParams() : int;

    /**
     * @param array $values
     * @return array
     */
    public function parseValues(array $values) : array;

    /**
     * Context 的默认值.
     * @return array
     */
    public function getDefaultValues() : array;

    /**
     * @param ParamDef $param
     */
    public function addParamDef(ParamDef $param) : void;

}