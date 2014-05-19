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

class Csv extends File implements OutputTypeInterface
{
    const DELIMITER = ';';

    public function store(array $data = array())
    {
        fputcsv($this->handle, array_values($data), self::DELIMITER);
    }
}
 