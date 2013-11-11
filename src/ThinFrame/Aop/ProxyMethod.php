<?php

/**
 * /src/ThinFrame/Aop/ProxyMethod.php
 *
 * @copyright 2013 Sorin Badea <sorin.badea91@gmail.com>
 * @license   MIT license (see the license file in the root directory)
 */

namespace ThinFrame\Aop;

use CG\Generator\PhpMethod;
use ThinFrame\Aop\Exceptions\ProxyMethodException;

/**
 * Class ProxyMethod
 *
 * @package ThinFrame\Aop
 * @since   0.2
 */
class ProxyMethod extends PhpMethod
{
    /**
     * @var ProxyMethod
     */
    private $method;

    /**
     * Constructor
     *
     * @param PhpMethod $method
     */
    public function __construct(PhpMethod $method)
    {
        parent::__construct($method->getName());
        $this->method = $method;
        $this->createAsProxy();
    }

    /**
     * Generate proxy method
     *
     * @throws Exceptions\ProxyMethodException
     */
    private function createAsProxy()
    {
        if ($this->method->isFinal()) {
            throw new ProxyMethodException('Cannot proxy a final method', $this);
        }
        if ($this->method->isStatic()) {
            throw new ProxyMethodException('Cannot proxy a static method', $this);
        }
        if ($this->method->getVisibility() == 'private') {
            throw new ProxyMethodException('Cannot proxy a private method', $this);
        }
        if ($this->method->isAbstract()) {
            throw new ProxyMethodException('Cannot proxy a abstract method', $this);
        }
        $this->setParameters($this->method->getParameters());
        $this->setVisibility($this->method->getVisibility());
        $this->setDocblock($this->method->getDocblock());
        $body = <<< EOT
        \$options = [
            'className' => get_parent_class(\$this),
            'method'    => __FUNCTION__,
            'instance'  => \$this,
            'arguments' => func_get_args()
        ];
        \$this->dispatcher->trigger(new \ThinFrame\Aop\AopEvent(\ThinFrame\Aop\AopEvent::BEFORE, \$options));
        \$response = call_user_func_array("parent::" . __FUNCTION__, func_get_args());
        \$options['response'] = \$response;
        \$this->dispatcher->trigger(new \ThinFrame\Aop\AopEvent(\ThinFrame\Aop\AopEvent::AFTER, \$options));
        return \$response;
EOT;
        $this->setBody($body);
    }
}
