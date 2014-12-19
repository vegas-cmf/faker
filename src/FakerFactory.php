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

namespace Vegas\Tool\Faker;

use Faker\Factory;

/**
 * Class FakerFactory
 * @package Vegas\Tool\Faker
 */
class FakerFactory extends Factory
{
    /**
     * Array of data class providers
     *
     * @var array
     */
    protected static $providersProxy = [];

    /**
     * Prepares data providers defined in specification file
     *
     * @param $spec
     * @param string $locale
     * @return \Faker\Generator
     */
    public static function createFromSpec($spec, $locale = self::DEFAULT_LOCALE)
    {
        $faker = self::create($locale);

        foreach ($spec as $key => $providerConfig) {
            $provider = self::extractProvider($providerConfig);
            $faker->addProvider($provider->instantiateProvider($faker));

            ProviderProxyMap::add($key, $provider);
        }

        return $faker;
    }

    /**
     * Extracts provider class name, method and parameters
     *
     * @param $providerConfig
     * @return ProviderProxy
     */
    protected static function extractProvider($providerConfig)
    {
        $providerName = $providerConfig['provider'];
        //extracts locale
        if (strpos($providerName, '/') !== false) {
            $providerSplitName = explode('/', $providerName);
            $locale = $providerSplitName[0];
            $providerName = $providerSplitName[1];
        } else {
            $locale = self::DEFAULT_LOCALE;
        }
        //extracts class and method name
        $providerSplitName = explode('::', $providerName);
        $providerName = $providerSplitName[0];
        $providerFunction = $providerSplitName[1];

        //finds the full provider class name
        $providerClassName = self::getProviderClassname($providerName, $locale);

        //provider name is defined in the same array with parameters
        //$providerConfig should contain only parameters that will be passed to method
        unset($providerConfig['provider']);

        //creates provider proxy
        $providerProxy = new ProviderProxy($providerClassName, $providerFunction, $providerConfig);
        return $providerProxy;
    }
}
 