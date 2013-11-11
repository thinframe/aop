<?php

/**
 * /src/ThinFrame/Aop/AopProxy.php
 *
 * @copyright 2013 Sorin Badea <sorin.badea91@gmail.com>
 * @license   MIT license (see the license file in the root directory)
 */

namespace ThinFrame\Aop;

use CG\Core\DefaultNamingStrategy;
use CG\Proxy\Enhancer;

/**
 * Class AopProxy
 *
 * @package ThinFrame\Aop
 */
class AopProxy extends Enhancer
{
    /**
     * @var string
     */
    private $className;
    /**
     * @var string
     */
    private $proxyClassName;
    /**
     * @var bool
     */
    private $loaded = false;
    /**
     * @var string
     */
    private $body = "";

    /**
     * Constructor
     *
     * @param string $className
     */
    public function __construct($className)
    {
        $this->className = $className;
        parent::__construct(
            new \ReflectionClass($className),
            ['\ThinFrame\Events\DispatcherAwareInterface']
        );
        $this->setUp();
    }

    /**
     * Set up enhancer
     */
    private function setUp()
    {
        $this->setNamingStrategy(new DefaultNamingStrategy('ThinFrameAopProxy'));
        $this->setGeneratorStrategy(new ProxyGeneratorStrategy());
    }

    /**
     * Get name for the proxy class
     *
     * @return mixed
     */
    public function getProxyClassName()
    {
        $this->parseClass();

        return $this->proxyClassName;
    }

    /**
     * parse class
     */
    private function parseClass()
    {
        if ($this->loaded) {
            return;
        }

        $this->body = $this->generateClass();
        $before     = get_declared_classes();
        eval($this->body);
        $after                = get_declared_classes();
        $loadedClasses        = array_diff($after, $before);
        $this->proxyClassName = $loadedClasses[key($loadedClasses)];
    }

    /**
     * Get body for the proxy class
     *
     * @return string
     */
    public function getBody()
    {
        return $this->body;
    }
}
