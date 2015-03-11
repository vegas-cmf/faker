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

/**
 * Class ProviderProxyMap
 *
 * Simple map for storing providers proxy
 *
 * @package Vegas\Tool\Faker
 */
class ProviderProxyMap
{
    /**
     * Associative array of providers mapped to key-string
     *
     * @var array
     */
    protected static $providersMap = [];

    /**
     * Adds new provider proxy identified by key
     *
     * @param $key
     * @param ProviderProxy $providerProxy
     */
    public static function add($key, ProviderProxy $providerProxy)
    {
        self::$providersMap[$key] = $providerProxy;
    }

    /**
     * Obtains provider proxy by key
     *
     * @param $key
     * @return null|ProviderProxy
     */
    public static function get($key)
    {
        if (!isset(self::$providersMap[$key])) {
            return null;
        }

        return self::$providersMap[$key];
    }
}
 