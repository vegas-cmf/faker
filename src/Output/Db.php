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

namespace Vegas\Tool\Faker\Output;

use Vegas\Tool\Faker\OutputAbstract;

abstract class Db extends OutputAbstract
{
    protected $model;

    /**
     * @return mixed
     */
    public function init()
    {
        $modelClassName = sprintf('\Vegas\Tool\Faker\Output\Db\Model%s', str_replace(__CLASS__, '', get_class($this)));
        $reflectionClass = new \ReflectionClass($modelClassName);
        $this->model = $reflectionClass->newInstance();
        $this->model->setSource($this->destination);
    }

    /**
     * @param array $data
     */
    public function store(array $data = array())
    {
        $this->model->writeAttributes($data);
        $this->model->save();
        $this->init();
    }
}
 