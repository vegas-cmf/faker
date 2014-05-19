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

use Faker\Factory;

class FakerFactory extends Factory
{
    protected static $providersProxy = array();

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

    protected static function extractProvider($providerConfig)
    {
        $providerName = $providerConfig['provider'];
        if (strpos($providerName, '/') !== false) {
            $providerSplittedName = explode('/', $providerName);
            $locale = $providerSplittedName[0];
            $providerName = $providerSplittedName[1];
        } else {
            $locale = self::DEFAULT_LOCALE;
        }
        $providerSplittedName = explode('::', $providerName);
        $providerName = $providerSplittedName[0];
        $providerFunction = $providerSplittedName[1];

        $providerClassName = self::getProviderClassname($providerName, $locale);

        unset($providerConfig['provider']);

        $providerProxy = new ProviderProxy($providerClassName, $providerFunction, $providerConfig);
        return $providerProxy;
    }
}
 