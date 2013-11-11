<?php

/**
 * /src/ThinFrame/Aop/AopEvent.php
 *
 * @copyright 2013 Sorin Badea <sorin.badea91@gmail.com>
 * @license   MIT license (see the license file in the root directory)
 */

namespace ThinFrame\Aop;

use ThinFrame\Events\AbstractEvent;

/**
 * Class AopEvent
 *
 * @package ThinFrame\Aop
 * @since   0.2
 */
final class AopEvent extends AbstractEvent
{
    const BEFORE = 'thinframe.aop.before';
    const AFTER  = 'thinframe.aop.after';
}
