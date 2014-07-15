<?php
/**
 * This file is part of Vegas package
 *
 * @author Slawomir Zytko <slawomir.zytko@gmail.com>
 * @copyright Amsterdam Standard Sp. Z o.o.
 * @homepage http://vegas-cmf.github.io
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */ 

namespace Vegas\Tool\Faker\Exception;

use Vegas\Tool\Faker\Exception as FakerException;

/**
 * Class MissingAdapterTypeException
 * @package Vegas\Tool\Faker\Exception
 */
class MissingAdapterTypeException extends FakerException
{
    protected $message = 'Missing adapter type';
}
 