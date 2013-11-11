<?php

/**
 * /src/ThinFrame/Aop/ProxyGeneratorStrategy.php
 *
 * @copyright 2013 Sorin Badea <sorin.badea91@gmail.com>
 * @license   MIT license (see the license file in the root directory)
 */

namespace ThinFrame\Aop;

use CG\Core\GeneratorStrategyInterface;
use CG\Generator\DefaultNavigator;
use CG\Generator\DefaultVisitor;
use CG\Generator\DefaultVisitorInterface;
use CG\Generator\PhpClass;
use CG\Generator\PhpProperty;
use ThinFrame\Aop\Exceptions\ProxyMethodException;

/**
 * Class ProxyGeneratorStrategy
 *
 * @package ThinFrame\Aop
 * @since   0.2
 */
class ProxyGeneratorStrategy implements GeneratorStrategyInterface
{
    /**
     * @var \CG\Generator\DefaultNavigator
     */
    private $navigator;
    /**
     * @var \CG\Generator\DefaultVisitor
     */
    private $visitor;

    /**
     * Constructor
     *
     * @param DefaultVisitorInterface $visitor
     */
    public function __construct(DefaultVisitorInterface $visitor = null)
    {
        $this->navigator = new DefaultNavigator();
        $this->visitor   = $visitor ? : new DefaultVisitor();
    }

    /**
     * @param callable $func
     */
    public function setConstantSortFunc(\Closure $func = null)
    {
        $this->navigator->setConstantSortFunc($func);
    }

    /**
     * @param callable $func
     */
    public function setMethodSortFunc(\Closure $func = null)
    {
        $this->navigator->setMethodSortFunc($func);
    }

    /**
     * @param callable $func
     */
    public function setPropertySortFunc(\Closure $func = null)
    {
        $this->navigator->setPropertySortFunc($func);
    }

    /**
     * @param PhpClass $class
     *
     * @return string
     */
    public function generate(PhpClass $class)
    {

        $dispatcherContainer = new PhpProperty('dispatcher');
        $dispatcherContainer->setVisibility('protected');
        $class->setProperty($dispatcherContainer);

        try {
            $method = $class->getMethod('setDispatcher');
            $method->setBody('$this->dispatcher=$dispatcher;');
        } catch (\Exception $e) {
        }

        $this->generateAopMethodProxy(
            $class,
            PhpClass::fromReflection(new \ReflectionClass($class->getParentClassName()))
        );

        $this->visitor->reset();
        $this->navigator->accept($this->visitor, $class);

        return $this->visitor->getContent();
    }

    /**
     * Generate aop methods proxies
     *
     * @param PhpClass $aopClass
     * @param PhpClass $parentClass
     */
    private function generateAopMethodProxy(PhpClass $aopClass, PhpClass $parentClass)
    {
        foreach ($parentClass->getMethods() as $method) {
            /* @var $method \CG\Generator\PhpMethod */
            try {
                $aopClass->getMethod($method->getName());
            } catch (\InvalidArgumentException $e) {
                try {
                    $proxyMethod = new ProxyMethod($method);
                    $aopClass->setMethod($proxyMethod);
                } catch (ProxyMethodException $e) {
                }
            }
        }
        if ($parentClass->getParentClassName() != null) {
            $this->generateAopMethodProxy(
                $aopClass,
                PhpClass::fromReflection(new \ReflectionClass($parentClass->getParentClassName()))
            );
        }
    }
}
