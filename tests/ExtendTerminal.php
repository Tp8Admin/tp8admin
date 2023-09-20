<?php
// +----------------------------------------------------------------------
// | NewThink [ Think More,Think Better! ]
// +----------------------------------------------------------------------
// | Copyright (c) 2016~2030 http://www.sxqibo.com All rights reserved.
// +----------------------------------------------------------------------
// | 版权所有：山西岐伯信息科技有限公司
// +----------------------------------------------------------------------
// | Author: yanghongwei  Date:2023/9/6 Time:14:09
// +----------------------------------------------------------------------
use PHPUnit\Framework\TestCase;

class ExtendTerminal  extends TestCase
{
	/**
	 * 测试通过Proc获取命令行执行结果
	 * @return void
	 */
	public function testGetResultFromProc()
	{
		$terminal = new \ta\Terminal();
		$result = $terminal->getResultFromProc('npm -v');
		echo "NPM版本是：". $result;
		$this->assertIsString($result);
	}

	/**
	 * 测试通过Proc获取命令行执行结果
	 * @return void
	 */
	public function testGetPhpVersionFromProc()
	{
		$terminal = new \ta\Terminal();
		$result = $terminal->getResultFromProc('php -v');
		echo "PHP版本是：". $result;
		$this->assertIsString($result);
	}

}