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

namespace Vegas\Tests\Tool\Task;

use Vegas\Tool\Faker\Task\GeneratorTask;

class GeneratorTaskTest extends \PHPUnit_Framework_TestCase
{
    public function testShouldGenerateDataBasedOnGivenArguments()
    {
        $destination = TESTS_ROOT_DIR . '/fixtures/output.json';
        $specPath = TESTS_ROOT_DIR . '/fixtures/spec.json';
        $count = 2;
        $outputAdapter = 'file.json';

        $dispatcher = $this->getMock('\Phalcon\CLI\Dispatcher');
        $dispatcher->expects($this->any())
            ->method('getTaskName')
            ->willReturn('\Vegas\Tool\Faker\Task\GeneratorTask');
        $dispatcher->expects($this->any())
            ->method('getActionName')
            ->willReturn('generate');
        $dispatcher->expects($this->any())
            ->method('getParam')
            ->willReturn([
                'o' => $outputAdapter,
                'd' => $destination,
                'c' => $count,
                's' => $specPath
            ]);

        $task = new GeneratorTask();
        $task->getDI()->set('dispatcher', $dispatcher);

        $task->beforeExecuteRoute();
        $task->setupOptions();
        $task->generateAction();

        $content = $task->getOutput();

        $this->assertContains('Done', $content);
        $this->assertContains('Generated in', $content);
        $this->assertFileExists($destination);
        $this->assertSame($count, count(json_decode(utf8_encode(file_get_contents($destination)), true)));

        @unlink($destination);
    }
}
 