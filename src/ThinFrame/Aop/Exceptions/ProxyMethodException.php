<?php

/**
 * /src/ThinFrame/Aop/Exceptions/ProxyMethodException.php
 *
 * @copyright 2013 Sorin Badea <sorin.badea91@gmail.com>
 * @license   MIT license (see the license file in the root directory)
 */

namespace ThinFrame\Aop\Exceptions;

use ThinFrame\Aop\ProxyMethod;
use ThinFrame\Foundation\Exceptions\RuntimeException;

/**
 * Class ProxyMethodException
 *
 * @package ThinFrame\Aop\Exceptions
 * @since   0.2
 */
class ProxyMethodException extends RuntimeException
{
    /**
     * @var ProxyMethod
     */
    private $method;

    /**
     * Constructor
     *
     * @param string      $message
     * @param ProxyMethod $method
     */
    public function __construct($message, ProxyMethod $method)
    {
        parent::__construct($message);
        $this->method = $method;
    }

    /**
     * Get generated proxy method
     *
     * @return ProxyMethod
     */
    public function getProxyMethod()
    {
        return $this->method;
    }
}
