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

namespace Vegas\Tool\Faker\Output;

use Vegas\Tool\Faker\OutputAbstract;

/**
 * Class File
 * @package Vegas\Tool\Faker\Output
 */
abstract class File extends OutputAbstract
{
    /**
     * @var
     */
    protected $handle;

    /**
     * @return mixed|void
     */
    public function init()
    {
        if (!file_exists($this->destination)) {
            touch($this->destination);
        }
        $this->handle = fopen($this->destination, 'a');
    }

    /**
     *
     */
    public function finalize()
    {
        fclose($this->handle);
    }
}
 