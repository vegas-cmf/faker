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

use Vegas\Tool\Faker\Output\OutputTypeInterface;

abstract class OutputAbstract
{
    /**
     * @var string
     */
    protected $destination;

    /**
     * @param $dest
     */
    public function setDestination($dest)
    {
        $this->destination = $dest;
    }

    /**
     * @return mixed
     */
    abstract public function init();

    abstract public function store(array $data = array());

    public function finalize()
    {

    }
}
 