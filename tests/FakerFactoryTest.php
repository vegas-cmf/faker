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

namespace Vegas\Tests\Tool\Faker;

use Vegas\Tool\Faker\FakerFactory;
use Vegas\Tool\Faker\ProviderProxy;
use Vegas\Tool\Faker\ProviderProxyMap;

class FakerFactoryTest extends \PHPUnit_Framework_TestCase
{

    public function testShouldCreateProviderFromArray()
    {
        $spec = json_decode('{"title" : {
                "provider" : "en_US/Text::realText",
                "length" : 100
            }}',
            true
        );

        $faker = FakerFactory::createFromSpec($spec);

        $this->assertInstanceOf('\Faker\Generator', $faker);
        $this->assertInstanceOf('\Vegas\Tool\Faker\ProviderProxy', ProviderProxyMap::get('title'));
        $this->assertNull(ProviderProxyMap::get('fake'));
        $this->assertEquals('Faker\Provider\en_US\Text', ProviderProxyMap::get('title')->getProviderClassName());
        $this->assertInstanceOf('\Faker\Provider\en_US\Text', ProviderProxyMap::get('title')->instantiateProvider($faker));
        $this->assertInternalType('string', ProviderProxyMap::get('title')->invoke($faker));
        $this->assertLessThan(100, strlen(ProviderProxyMap::get('title')->invoke($faker)));
    }

    public function testShouldAddProviderForGivenKey()
    {
        $faker = FakerFactory::create();

        $proxy = new ProviderProxy('Faker\Provider\en_US\Person', 'firstName', []);
        ProviderProxyMap::add('firstName', $proxy);

        $this->assertNotNull(ProviderProxyMap::get('firstName'));
        $this->assertInstanceOf('\Vegas\Tool\Faker\ProviderProxy', ProviderProxyMap::get('firstName'));
        $this->assertInstanceOf('Faker\Provider\en_US\Person', ProviderProxyMap::get('firstName')->instantiateProvider($faker));
        $this->assertInternalType('string', ProviderProxyMap::get('firstName')->invoke($faker));
    }

    public function testShouldThrowExceptionForInvalidProvider()
    {
        $faker = FakerFactory::create();

        $proxy = new ProviderProxy('Faker\Provider\en_US\Fake', 'fake', []);
        try {
            $proxy->instantiateProvider($faker);

            throw new \Exception();
        } catch (\Exception $e) {
            $this->assertInstanceOf('\Vegas\Tool\Faker\Exception\UnableProviderInstantiateException', $e);
        }
    }
}
 