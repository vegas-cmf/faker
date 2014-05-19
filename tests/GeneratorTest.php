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

namespace Vegas\Tests\Tool\Faker;

use Vegas\Tool\Faker\Generator;

class GeneratorTest extends \PHPUnit_Framework_TestCase
{

    public function testCsvFile()
    {
        $path = dirname(__FILE__) . '/fixtures/spec.json';

        $generator = new Generator();
        $generator->setAdapter('file');
        $generator->setAdapterType('csv');
        $generator->setCount(5);
        $generator->setSpecFilePath($path);
        $generator->setDestination(dirname(__FILE__) . '/fixtures/output.csv');

        $generator->generate();
    }

    public function testXmlFile()
    {
        $path = dirname(__FILE__) . '/fixtures/spec.json';

        $generator = new Generator();
        $generator->setAdapter('file');
        $generator->setAdapterType('xml');
        $generator->setCount(5);
        $generator->setSpecFilePath($path);
        $generator->setDestination(dirname(__FILE__) . '/fixtures/output.xml');

        $generator->generate();
    }

    public function testJsonFile()
    {
        $path = dirname(__FILE__) . '/fixtures/spec.json';

        $generator = new Generator();
        $generator->setAdapter('file');
        $generator->setAdapterType('json');
        $generator->setCount(5);
        $generator->setSpecFilePath($path);
        $generator->setDestination(dirname(__FILE__) . '/fixtures/output.json');

        $generator->generate();
    }
}
 