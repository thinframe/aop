<?php

/**
 * /src/ThinFrame/Aop/DependencyInjection/AopCompilerPass.php
 *
 * @copyright 2013 Sorin Badea <sorin.badea91@gmail.com>
 * @license   MIT license (see the license file in the root directory)
 */

namespace ThinFrame\Aop\DependencyInjection;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use ThinFrame\Aop\AopProxy;

/**
 * Class AopCompilerPass
 *
 * @package ThinFrame\Aop\DependencyInjection
 * @since   0.2
 */
class AopCompilerPass implements CompilerPassInterface
{

    /**
     * @var ContainerBuilder
     */
    private $container;

    /**
     * You can modify the container here before it is dumped to PHP code.
     *
     * @param ContainerBuilder $container
     *
     * @api
     */
    public function process(ContainerBuilder $container)
    {
        $this->container = $container;
        array_map(
            [$this, 'createProxyForService'],
            array_keys($this->container->findTaggedServiceIds('thinframe.aop'))
        );
    }

    /**
     * @param $serviceId
     */
    public function createProxyForService($serviceId)
    {
        $definition = $this->container->getDefinition($serviceId);
        $aopProxy   = new AopProxy($definition->getClass());
        $definition->setClass($aopProxy->getProxyClassName());
    }
}
