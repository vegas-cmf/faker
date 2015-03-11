<?php
/**
 * This file is part of Vegas package
 *
 * @author Slawomir Zytko <slawek@amsterdam-standard.pl>
 * @copyright Amsterdam Standard Sp. Z o.o.
 * @homepage https://vegas-cmf.github.io
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */ 

namespace Vegas\Tool\Faker;
use Vegas\Tool\Faker\Exception\InvalidOutputAdapterException;
use Vegas\Tool\Faker\Exception\InvalidSpecFileException;
use Vegas\Tool\Faker\Exception\MissingAdapterException;
use Vegas\Tool\Faker\Exception\MissingAdapterTypeException;
use Vegas\Tool\Faker\Exception\MissingDestinationException;

/**
 * Class Generator
 *
 * Class that generates random data, described by specification file.
 * Specification file must be in JSON format.
 * Usage:
 *
 * <code>
 * $generator = new Generator();
 * $generator->setAdapter('file');
 * $generator->setAdapterType('xml');
 * $generator->setCount(5);
 * $generator->setSpecFilePath($path);
 * $generator->setDestination($outputPath);
 *
 * $generator->generate();
 * </code>
 *
 * @use https://github.com/fzaninotto/faker
 * @package Vegas\Tool\Faker
 */
class Generator
{
    /**
     * @var string
     */
    private $adapterName;

    /**
     * @var string
     */
    private $adapterType;

    /**
     * @var string
     */
    private $specFilePath;

    /**
     * @var int
     */
    private $count = 1;

    /**
     * @var string
     */
    private $destination;

    /**
     * @var array
     */
    private $customProviders = [];

    /**
     * Name of output adapter
     *
     * @param $adapterName
     * @return $this
     */
    public function setAdapter($adapterName)
    {
        $this->adapterName = $adapterName;

        return $this;
    }

    /**
     * End output.
     * This class will store generated data in specified endpoint.
     *
     * @param $adapterType
     * @return $this
     */
    public function setAdapterType($adapterType)
    {
        $this->adapterType = $adapterType;

        return $this;
    }

    /**
     * Sets file path containing specification
     *
     * @param $specFilePath
     * @return $this
     */
    public function setSpecFilePath($specFilePath)
    {
        $this->specFilePath = $specFilePath;

        return $this;
    }

    /**
     * Number of generated rows
     *
     * @param int $count
     * @return $this
     */
    public function setCount($count = 1)
    {
        $this->count = $count;

        return $this;
    }

    /**
     * Sets destination name for output
     * It can be a file path or database table/collection
     *
     * @param $destination
     * @return $this
     */
    public function setDestination($destination)
    {
        $this->destination = $destination;

        return $this;
    }

    /**
     * @param $adapter
     * @param $type
     * @return OutputAbstract
     * @throws Exception\InvalidOutputAdapterException
     */
    private function obtainOutputAdapter($adapter, $type)
    {
        try {
            $outputNamespace = __NAMESPACE__ . '\Output\\' . ucfirst($adapter);
            $outputTypeNamespace = $outputNamespace . '\\' . ucfirst($type);
            $typeReflectionClass = new \ReflectionClass($outputTypeNamespace);
            $typeInstance = $typeReflectionClass->newInstance();

            return $typeInstance;
        } catch (\ReflectionException $ex) {
            throw new InvalidOutputAdapterException();
        }
    }

    /**
     * Fetches specification for generator in JSON format
     *
     * @param $specFilePath
     * @return mixed
     * @throws Exception\InvalidSpecFileException
     */
    protected function fetchDataSpec($specFilePath)
    {
        if (!file_exists($specFilePath)) {
            throw new InvalidSpecFileException();
        }

        $specFileContent = file_get_contents($specFilePath);
        $spec = json_decode($specFileContent, true);

        return $spec;
    }

    /**
     * Adds custom data provider
     * Custom provider should be in the namespace \Faker\Provider
     *
     * @param string $customProviderName
     * @return $this
     */
    public function addCustomProvider($customProviderName)
    {
        $this->customProviders[] = $customProviderName;
        return $this;
    }

    /**
     * Generates fake data
     *
     * @throws Exception\MissingDestinationException
     * @throws Exception\MissingAdapterTypeException
     * @throws Exception\MissingAdapterException
     */
    public function generate()
    {
        //fetches specification
        $spec = $this->fetchDataSpec($this->specFilePath);

        if (!$this->adapterName) {
            throw new MissingAdapterException();
        }
        if (!$this->adapterType) {
            throw new MissingAdapterTypeException();
        }
        if (!$this->destination) {
            throw new MissingDestinationException();
        }

        //prepares output adapter
        $outputAdapter = $this->obtainOutputAdapter($this->adapterName, $this->adapterType);
        $outputAdapter->setDestination($this->destination);
        $outputAdapter->init();

        //prepares fake data generator
        $faker = FakerFactory::createFromSpec($spec);
        foreach ($this->customProviders as $provider) {
            $reflectionClass = new \ReflectionClass($provider);
            $faker->addProvider($reflectionClass->newInstance($faker));
        }

        //generates data`
        for ($i = 0; $i < $this->count; $i++) {
            $data = [];
            foreach ($spec as $key => $providerConfig) {
                $providerProxy = ProviderProxyMap::get($key);
                $data[$key] = $providerProxy->invoke($faker);
            }

            $outputAdapter->store($data);
        }

        //release, clean up...
        $outputAdapter->finalize();
    }
}
 