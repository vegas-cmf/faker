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
 * Class Xml
 * @package Vegas\Tool\Faker\Output\File
 */
class Xml extends File implements OutputTypeInterface
{

    /**
     * @return mixed|void
     */
    public function init()
    {
        $xmlString = <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<fake></fake>
XML;
        $this->handle = new \SimpleXMLElement($xmlString);
    }

    /**
     * @param array $data
     * @return mixed|void
     */
    public function store(array $data = array())
    {
        $node = $this->handle->addChild('data');
        foreach ($data as $key => $value) {
            $node->addChild($key, $value);
        }
    }

    /**
     *
     */
    public function finalize()
    {
        $this->handle->asXML($this->destination);
    }
}
 