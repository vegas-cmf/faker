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

namespace Vegas\Tool\Faker\Output\Db\Model;

/**
 * Class Orm
 * @package Vegas\Tool\Faker\Output\Db\Model
 */
class Orm extends \Vegas\Db\Decorator\ModelAbstract
{
    /**
     * @param $source
     * @return \Phalcon\Mvc\Model|void
     */
    public function setSource($source)
    {
        parent::setSource($source);
    }
}
 