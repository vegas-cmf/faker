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

class Xml extends File implements OutputTypeInterface
{
    public function init()
    {
        $xmlString = <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<fake></fake>
XML;
        $this->handle = new \SimpleXMLElement($xmlString);
    }

    public function store(array $data = array())
    {
        $node = $this->handle->addChild('data');
        foreach ($data as $key => $value) {
            $node->addChild($key, $value);
        }
    }

    public function finalize()
    {
        $this->handle->asXML($this->destination);
    }
}
 