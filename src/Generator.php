<?php
/**
 * This file is part of Vegas package
 *
 * @author Slawomir Zytko <slawomir.zytko@gmail.com>
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
 * @use https://github.com/fzaninotto/faker
 * @package Vegas\Tool\Faker
 */
class Generator
{
    private $adapterName;
    private $adapterType;
    private $specFilePath;
    private $count = 1;
    private $destination;

    public function setAdapter($adapterName)
    {
        $this->adapterName = $adapterName;

        return $this;
    }

    public function setAdapterType($adapterType)
    {
        $this->adapterType = $adapterType;

        return $this;
    }

    public function setSpecFilePath($specFilePath)
    {
        $this->specFilePath = $specFilePath;

        return $this;
    }

    public function setCount($count = 1)
    {
        $this->count = $count;

        return $this;
    }

    public function setDestination($dest)
    {
        $this->destination = $dest;

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

    protected function fetchDataSpec($specFilePath)
    {
        if (!file_exists($specFilePath)) {
            throw new InvalidSpecFileException();
        }

        $specFileContent = file_get_contents($specFilePath);
        $spec = json_decode($specFileContent, true);

        return $spec;
    }

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

        //prepare fake data generator with provider proxies
        $faker = FakerFactory::createFromSpec($spec);

        //generate data`
        for ($i = 0; $i < $this->count; $i++) {
            $data = array();
            foreach ($spec as $key => $providerConfig) {
                $providerProxy = ProviderProxyMap::get($key);
                if (null == $providerProxy) {
                    continue;
                }
                $data[$key] = $providerProxy->invoke($faker);
            }

            $outputAdapter->store($data);
        }

        $outputAdapter->finalize();
    }
}
 