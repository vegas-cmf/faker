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
 * Class OutputAbstract
 * @package Vegas\Tool\Faker
 */
abstract class OutputAbstract
{
    /**
     * @var string
     */
    protected $destination;

    /**
     * Sets the destination for generated data
     * It can be a file path or database table/collection
     *
     * @param $dest
     */
    public function setDestination($dest)
    {
        $this->destination = $dest;
    }

    /**
     * Some initialization for output
     *
     * @return mixed
     */
    abstract public function init();

    /**
     * Stores generated data
     *
     * @param array $data
     * @return mixed
     */
    abstract public function store(array $data = []);

    /**
     * Finalizes data generation
     */
    public function finalize()
    {

    }
}
 