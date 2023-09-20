<?php
declare (strict_types = 1);

namespace app\command;

use think\console\Command;
use think\console\Input;
use think\console\Output;

class TestPhpVersion extends Command
{
    protected function configure()
    {
        // 指令配置
        $this->setName('command:getPhpVersion')
            ->setDescription('获取PHP版本');
    }

    protected function execute(Input $input, Output $output)
    {
	    $terminal = new \ta\Terminal();
	    $result = $terminal->getResultFromProc('php -v');
        // 指令输出
        $output->writeln('PHP版本是：'.$result);
    }
}
