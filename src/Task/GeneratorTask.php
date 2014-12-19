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

namespace Vegas\Tool\Faker\Task;

use Vegas\Cli\Task\Action;
use Vegas\Cli\Task\Option;

/**
 * Class GeneratorTask
 */
class GeneratorTask extends \Vegas\Cli\Task
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
        $benchmarkStart = time();

        $output = $this->getOption('o');
        $dest = $this->getOption('d');
        $spec = $this->getOption('s');
        $count = $this->getOption('c');

        $generator = new \Vegas\Tool\Faker\Generator();
        $generator->setDestination($dest);
        $generator->setSpecFilePath($spec);
        $generator->setCount($count);

        $outputParts = explode('.', $output);
        $generator->setAdapter($outputParts[0]);
        $generator->setAdapterType($outputParts[1]);

        $generator->generate();

        $benchmarkFinish = time();

        $this->putSuccess('Done.');
        $this->putText('Generated in: ' . ($benchmarkFinish - $benchmarkStart) . ' seconds');
    }
}
 