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

namespace Vegas\Tool\Faker\Output\File;

use Vegas\Tool\Faker\Output\File;
use Vegas\Tool\Faker\Output\OutputTypeInterface;

/**
 * Class Csv
 * @package Vegas\Tool\Faker\Output\File
 */
class Csv extends File implements OutputTypeInterface
{
    const DELIMITER = ';';

    /**
     * @param array $data
     * @return mixed|void
     */
    public function store(array $data = [])
    {
        fputcsv($this->handle, array_values($data), self::DELIMITER, '"');
    }
}
 