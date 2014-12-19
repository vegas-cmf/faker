<?php
/**
 * This file is part of Vegas package
 *
 * @author Slawomir Zytko <slawomir.zytko@gmail.com>
 * @copyright Amsterdam Standard Sp. Z o.o.
 * @homepage http://vegas-cmf.github.io
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Vegas\Tool\Faker\Output\File;

use Vegas\Tool\Faker\Output\File;
use Vegas\Tool\Faker\Output\OutputTypeInterface;

/**
 * Class Json
 * @package Vegas\Tool\Faker\Output\File
 */
class Json extends File implements OutputTypeInterface
{
    protected $dataArray = array();

    /**
     * @param array $data
     * @return mixed|void
     */
    public function store(array $data = [])
    {
        $this->dataArray[] = $data;
    }

    /**
     *
     */
    public function finalize()
    {
        fputs($this->handle, json_encode($this->dataArray));
        parent::finalize();
    }
}