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

use Vegas\Db\Decorator\CollectionAbstract;
use Vegas\Db\Decorator\ModelAbstract;
use Vegas\Tool\Faker\Generator;

class OdmModel extends CollectionAbstract
{
    public function getSource()
    {
        return 'fake';
    }
}
class OrmModel extends ModelAbstract
{
    public function getSource()
    {
        return 'fake';
    }
}

class GeneratorTest extends \PHPUnit_Framework_TestCase
{

    public function testCsvFile()
    {
        $path = dirname(__FILE__) . '/fixtures/spec.json';
        $outputPath = dirname(__FILE__) . '/fixtures/output.csv';
        @unlink($outputPath);

        $generator = new Generator();
        $generator->setAdapter('file');
        $generator->setAdapterType('csv');
        $generator->setCount(5);
        $generator->setSpecFilePath($path);
        $generator->setDestination($outputPath);

        $generator->generate();

        $this->assertTrue(file_exists($outputPath));

        $count = 0;
        $handle = fopen($outputPath, 'r');
        while (fgetcsv($handle, null, ';', '"')) $count++;
        $this->assertSame(5, $count);
    }

    public function testXmlFile()
    {
        $path = dirname(__FILE__) . '/fixtures/spec.json';
        $outputPath = dirname(__FILE__) . '/fixtures/output.xml';
        @unlink($outputPath);

        $generator = new Generator();
        $generator->setAdapter('file');
        $generator->setAdapterType('xml');
        $generator->setCount(5);
        $generator->setSpecFilePath($path);
        $generator->setDestination($outputPath);

        $generator->generate();

        $this->assertTrue(file_exists($outputPath));

        $xml = simplexml_load_file($outputPath);
        $this->assertSame(5, $xml->count());
    }

    public function testJsonFile()
    {
        $path = dirname(__FILE__) . '/fixtures/spec.json';
        $outputPath = dirname(__FILE__) . '/fixtures/output.json';
        @unlink($outputPath);

        $generator = new Generator();
        $generator->setAdapter('file');
        $generator->setAdapterType('json');
        $generator->setCount(5);
        $generator->setSpecFilePath($path);
        $generator->setDestination($outputPath);

        $generator->generate();

        $this->assertTrue(file_exists($outputPath));

        $json = json_decode(file_get_contents($outputPath));
        $this->assertSame(5, count($json));
    }

    public function testODMFile()
    {
        foreach (OdmModel::find() as $d) { $d->delete(); }

        $path = dirname(__FILE__) . '/fixtures/spec.json';

        $generator = new Generator();
        $generator->setAdapter('db');
        $generator->setAdapterType('odm');
        $generator->setCount(5);
        $generator->setSpecFilePath($path);
        $generator->setDestination('fake');

        $generator->generate();

        $this->assertSame(5, OdmModel::count());
    }

    public function testORMFile()
    {
        foreach (OrmModel::find() as $d) { $d->delete(); }

        $path = dirname(__FILE__) . '/fixtures/spec.json';

        $generator = new Generator();
        $generator->setAdapter('db');
        $generator->setAdapterType('orm');
        $generator->setCount(5);
        $generator->setSpecFilePath($path);
        $generator->setDestination('fake');

        $generator->generate();

        $this->assertSame(5, intval(OrmModel::count()));
    }

    public function testCustomProvider()
    {
        require dirname(__FILE__) . '/fixtures/Foo.php';
        $path = dirname(__FILE__) . '/fixtures/foospec.json';
        $outputPath = dirname(__FILE__) . '/fixtures/foo.json';
        @unlink($outputPath);

        $generator = new Generator();
        $generator->setSpecFilePath($path);
        $generator->setAdapter('file');
        $generator->setAdapterType('json');
        $generator->setCount(10);
        $generator->setDestination($outputPath);
        $generator->addCustomProvider('\Faker\Provider\Foo');

        $generator->generate();

        $foo = json_decode(file_get_contents($outputPath), true);
        $this->assertSame(10, count($foo));
        $this->assertEquals(file_get_contents($outputPath), json_encode($foo));
        $this->assertTrue(in_array('bool', array_keys($foo)));
    }
}
 