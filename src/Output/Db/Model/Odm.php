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

namespace Vegas\Tool\Faker\Output\Db\Model;

/**
 * Class Odm
 * @package Vegas\Tool\Faker\Output\Db\Model
 */
class Odm extends \Vegas\Db\Decorator\CollectionAbstract
{
    /**
     * @param $source
     * @return \Phalcon\Mvc\Collection|void
     */
    public function setSource($source)
    {
        parent::setSource($source);
    }
}