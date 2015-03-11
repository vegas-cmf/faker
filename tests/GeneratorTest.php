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

use Phalcon\DI;
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
    public static function setUpBeforeClass()
    {
        $db = DI::getDefault()->get('db');
        $db->execute('DROP TABLE IF EXISTS fake ');
        $db->execute(
            'CREATE TABLE fake(
            id int not null primary key auto_increment,
            title varchar(250) null,
            content text null,
            email varchar(250) null,
            first_name varchar(250) null,
            last_name varchar(250) null,
            address varchar(250) null,
            date_of_birth datetime null,
            company varchar(250) null,
            phone_number varchar(250) null,
            number_of_cars int(2) null,
            homepage varchar(250) null,
            last_visited_page varchar(250) null,
            user_agent varchar(250) null,
            color varchar(250) null,
            picture varchar(250) null,
            background varchar(250) null,
            password_hash varchar(250) null,
            locale varchar(250) null
            );'
        );
    }

    public static function tearDownAfterClass()
    {
        $di = DI::getDefault();
        $di->get('db')->execute('DROP TABLE IF EXISTS fake ');
    }

    public function testShouldGenerateDataFromCsvFile()
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

        @unlink($outputPath);
    }

    public function testShouldGenerateDataFromXmlFile()
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

        @unlink($outputPath);
    }

    public function testShouldGenerateDataFromJsonFile()
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

        @unlink($outputPath);
    }

    public function testShouldGenerateDataToODM()
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

    public function testShouldGenerateDataToORM()
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

    public function testShouldGenerateDataUsingCustomProvider()
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

    public function testShouldThrowExceptionForInvalidAdapter()
    {
        $path = dirname(__FILE__) . '/fixtures/foospec.json';
        $outputPath = dirname(__FILE__) . '/fixtures/foo.json';

        $generator = new Generator();
        $generator->setSpecFilePath($path);
        $generator->setAdapterType('json');
        $generator->setCount(10);
        $generator->setDestination($outputPath);

        try {
            $generator->generate();

            throw new \Exception();
        } catch (\Exception $e) {
            $this->assertInstanceOf('\Vegas\Tool\Faker\Exception\MissingAdapterException', $e);
        }
    }

    public function testShouldThrowExceptionForInvalidAdapterType()
    {
        $path = dirname(__FILE__) . '/fixtures/foospec.json';
        $outputPath = dirname(__FILE__) . '/fixtures/foo.json';

        $generator = new Generator();
        $generator->setAdapter('file');
        $generator->setSpecFilePath($path);
        $generator->setCount(10);
        $generator->setDestination($outputPath);

        try {
            $generator->generate();

            throw new \Exception();
        } catch (\Exception $e) {
            $this->assertInstanceOf('\Vegas\Tool\Faker\Exception\MissingAdapterTypeException', $e);
        }
    }

    public function testShouldThrowExceptionForInvalidDestination()
    {
        $path = dirname(__FILE__) . '/fixtures/foospec.json';

        $generator = new Generator();
        $generator->setSpecFilePath($path);
        $generator->setAdapter('file');
        $generator->setAdapterType('json');
        $generator->setCount(10);

        try {
            $generator->generate();

            throw new \Exception();
        } catch (\Exception $e) {
            $this->assertInstanceOf('\Vegas\Tool\Faker\Exception\MissingDestinationException', $e);
        }
    }

    public function testShouldThrowExceptionForOutputAdapter()
    {
        $path = dirname(__FILE__) . '/fixtures/foospec.json';
        $outputPath = dirname(__FILE__) . '/fixtures/foo.json';

        $generator = new Generator();
        $generator->setSpecFilePath($path);
        $generator->setAdapter('file');
        $generator->setAdapterType('html');
        $generator->setCount(10);
        $generator->setDestination($outputPath);

        try {
            $generator->generate();

            throw new \Exception();
        } catch (\Exception $e) {
            $this->assertInstanceOf('\Vegas\Tool\Faker\Exception\InvalidOutputAdapterException', $e);
        }
    }

    public function testShouldThrowExceptionForMissingSpecFile()
    {
        $path = dirname(__FILE__) . '/fixtures/fake.json';
        $outputPath = dirname(__FILE__) . '/fixtures/foo.json';

        $generator = new Generator();
        $generator->setSpecFilePath($path);
        $generator->setAdapter('file');
        $generator->setAdapterType('json');
        $generator->setCount(10);
        $generator->setDestination($outputPath);

        try {
            $generator->generate();

            throw new \Exception();
        } catch (\Exception $e) {
            $this->assertInstanceOf('\Vegas\Tool\Faker\Exception\InvalidSpecFileException', $e);
        }
    }
}
 