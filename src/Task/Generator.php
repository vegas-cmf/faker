<?php
use Vegas\Cli\Task\Action;
use Vegas\Cli\Task\Option;
use Vegas\Tool\Faker\Exception\InvalidOutputAdapterException;

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

class Generator extends \Vegas\Cli\Task
{

    /**
     * Task must implement this method to set available options
     *
     * @return mixed
     */
    public function setOptions()
    {
        $action = new Action('generate', 'Generate fake data');
        //output adapter
        $option = new Option('o', 'output', 'Specify output adapter. Available outputs: db.[orm|odm], file.[csv|json|xml]');
        $option->setRequired(true);
        $action->addOption($option);

        $option = new Option('d', 'dest', 'Specify the destination point. It might be a file, or database collection or table');
        $option->setRequired(true);
        $action->addOption($option);

        //data specification
        $option = new Option('s', 'spec', 'Specify the file path containing data specification in JSON format');
        $option->setRequired(true);
        $action->addOption($option);

        //count of data
        $option = new Option('c', 'count', 'Specify the count of data to generate');
        $option->setRequired(true);
        $action->addOption($option);

        $this->addTaskAction($action);
    }

    public function generateAction()
    {
        $output = $this->getOption('o');
        $dest = $this->getOption('d');
        $spec = $this->getOption('s');
        $count = $this->getOption('c');

        $generator = new \Vegas\Tool\Faker\Generator();

    }
}
 