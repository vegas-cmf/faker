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

namespace Vegas\Tool\Faker\Output\File;

use Vegas\Tool\Faker\Output\File;
use Vegas\Tool\Faker\Output\OutputTypeInterface;

class Json extends File implements OutputTypeInterface
{
    protected $dataArray = array();

    public function store(array $data = array())
    {
        $this->dataArray[] = $data;
    }

    public function finalize()
    {
        fputs($this->handle, json_encode($this->dataArray));
        parent::finalize();
    }
}