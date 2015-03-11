<?php
/**
 * This file is part of Vegas package
 *
 * @author Slawomir Zytko <slawek@amsterdam-standard.pl>
 * @copyright Amsterdam Standard Sp. Z o.o.
 * @homepage http://vegas-cmf.github.io
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */ 

namespace Vegas\Tool\Faker;

use Vegas\Tool\Faker\Exception\UnableProviderInstantiateException;

/**
 * Class ProviderProxy
 * @package Vegas\Tool\Faker
 */
class ProviderProxy
{
    /**
     * The name of class that provides data
     *
     * @var
     */
    private $providerClassName;

    /**
     * The name of class's method that generates data
     *
     * @var
     */
    private $providerFunction;

    /**
     * Parameters of method
     *
     * @var
     */
    private $providerParameters;

    /**
     * @param $providerClassName
     * @param $providerFunction
     * @param $providerParameters
     */
    public function __construct($providerClassName, $providerFunction, $providerParameters)
    {
        $this->providerClassName = $providerClassName;
        $this->providerFunction = $providerFunction;
        $this->providerParameters = $providerParameters;
    }

    /**
     * @return mixed
     */
    public function getProviderClassName()
    {
        return $this->providerClassName;
    }

    /**
     * Creates an instance of provider
     *
     * @param \Faker\Generator $faker
     * @return object
     * @throws Exception\UnableProviderInstantiateException
     */
    public function instantiateProvider(\Faker\Generator $faker)
    {
        try {
            $reflectionClass = new \ReflectionClass($this->providerClassName);
            return $reflectionClass->newInstanceArgs([$faker]);
        } catch (\ReflectionException $ex) {
            throw new UnableProviderInstantiateException($this->providerClassName);
        }
    }

    /**
     * Invokes method on instantiated provider
     *
     * @param \Faker\Generator $faker
     * @return mixed
     */
    public function invoke(\Faker\Generator $faker)
    {
        return call_user_func_array(
            [$faker, $this->providerFunction],
            array_values($this->providerParameters)
        );
    }
}
 