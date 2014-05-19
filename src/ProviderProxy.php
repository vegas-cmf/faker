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

namespace Vegas\Tool\Faker;

use Vegas\Tool\Faker\Exception\UnableProviderInstantiateException;

class ProviderProxy
{
    private $providerClassName;
    private $providerFunction;
    private $providerParameters;

    public function __construct($providerClassName, $providerFunction, $providerParameters)
    {
        $this->providerClassName = $providerClassName;
        $this->providerFunction = $providerFunction;
        $this->providerParameters = $providerParameters;
    }

    public function getProviderClassName()
    {
        return $this->providerClassName;
    }

    public function instantiateProvider(\Faker\Generator $faker)
    {
        try {
            $reflectionClass = new \ReflectionClass($this->providerClassName);
            return $reflectionClass->newInstanceArgs(array($faker));
        } catch (\ReflectionException $ex) {
            throw new UnableProviderInstantiateException($this->providerClassName);
        }
    }

    public function invoke(\Faker\Generator $faker)
    {
        return call_user_func_array(array($faker, $this->providerFunction), array_values($this->providerParameters));
    }
}
 