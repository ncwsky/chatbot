<?php

/**
 * This file is part of CommuneChatbot.
 *
 * @link     https://github.com/thirdgerb/chatbot
 * @document https://github.com/thirdgerb/chatbot/blob/master/README.md
 * @contact  <thirdgerb@gmail.com>
 * @license  https://github.com/thirdgerb/chatbot/blob/master/LICENSE
 */

namespace Commune\Blueprint\Framework;

use Commune\Blueprint\Exceptions\HostBootingException;
use Commune\Framework\Contracts\ServiceProvider;


/**
 * 各种服务的注册中心.
 * 管理两类容器, 三类服务
 *
 * 两类容器:
 * - ProcContainer
 * - ReqContainer
 *
 * 三类服务:
 * - ConfigService : 配置
 * - ProcService : 进程级服务
 * - ReqService : 请求级服务
 *
 *
 * @author thirdgerb <thirdgerb@gmail.com>
 */
interface ServiceRegistrar
{
    /*----------- 服务注册 -----------*/

    /**
     * 注册配置相关的服务, 优先级最高.
     * @param ServiceProvider $provider
     * @param bool $top
     */
    public function registerConfigProvider(
        ServiceProvider $provider,
        bool $top
    ) : void;

    /**
     * 注册进程级的服务.
     * @param ServiceProvider $provider
     * @param bool $top
     */
    public function registerProcProvider(
        ServiceProvider $provider,
        bool $top
    ) : void;

    /**
     * 注册请求级服务.
     * @param ServiceProvider $provider
     * @param bool $top
     */
    public function registerReqProvider(
        ServiceProvider $provider,
        bool $top
    ) : void;


    /*----------- 获取服务 -----------*/

    /**
     * @return ServiceProvider[] string => ServiceProvider
     */
    public function getConfigProviders() : array;

    /**
     * @return ServiceProvider[] string => ServiceProvider
     */
    public function getProcProviders() : array;

    /**
     * @return ServiceProvider[] string => ServiceProvider
     */
    public function getReqProviders() : array;

    /*----------- Components -----------*/

    /**
     * 注册一个组件.
     * @param string $componentName
     * @param array $options
     */
    public function registerComponent(
        string $componentName,
        array $options
    ) : void;

    /**
     * 记录依赖一个组件
     * @param string $componentName
     * @param string $dependedBy
     * @param array $options
     */
    public function dependComponent(
        string $componentName,
        string $dependedBy,
        array $options = []
    ) : void;

    /**
     * 初始化所有的组件.
     */
    public function bootComponents() : void;

    /**
     * 组件是否都已经加载.
     * @return bool
     */
    public function isComponentsBooted() : bool;

    /*----------- 初始化服务 -----------*/

    /**
     * 启动配置类服务.
     * @return bool
     * @throws HostBootingException
     */
    public function bootConfigServices() : bool;


    /**
     * 启动进程类服务
     * @return bool
     * @throws HostBootingException
     */
    public function bootProcServices() : bool;

    /**
     * 启动请求类服务
     *
     * @throws HostBootingException
     * @param ReqContainer $container
     * @return bool
     */
    public function bootReqServices(ReqContainer $container) : bool;

    /*----------- 状态 -----------*/

    /**
     * @return bool
     */
    public function isConfigServicesBooted() : bool;

    /**
     * @return bool
     */
    public function isProcServicesBooted() : bool;

}