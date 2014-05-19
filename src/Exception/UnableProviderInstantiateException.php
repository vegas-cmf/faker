<?php
/**
 * This file is part of Vegas package
 *
 * @author Slawomir Zytko <slawomir.zytko@gmail.com>
 * @copyright Amsterdam Standard Sp. Z o.o.
 * @homepage https://github.com/vegas-cmf
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Vegas\Tool\Faker\Exception;

use Vegas\Tool\Faker\Exception as FakerException;

/**
 * Class UnableProviderInstantiateException
 * @package Vegas\Tool\Faker\Exception
 */
class UnableProviderInstantiateException extends FakerException
{
    protected $message = 'Unable to instantiate provider \'%s\'';

    /**
     * @param string $providerName
     */
    public function __construct($providerName)
    {
        $this->message = sprintf($this->message, $providerName);
    }
}
 