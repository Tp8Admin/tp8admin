<?php
// +----------------------------------------------------------------------
// | NewThink [ Think More,Think Better! ]
// +----------------------------------------------------------------------
// | Copyright (c) 2016~2030 http://www.sxqibo.com All rights reserved.
// +----------------------------------------------------------------------
// | 版权所有：山西岐伯信息科技有限公司
// +----------------------------------------------------------------------
// | Author: yanghongwei  Date:2023/9/6 Time:13:22
// +----------------------------------------------------------------------

namespace ta;

class Terminal
{

	/**
	 * 通过Proc获取命令行执行结果
	 * @param $commandName "eg: npm -v"
	 * @return false|string
	 */
	public function getResultFromProc(string $commandName): bool|string
	{
		$descriptorspec = [
			0 => array("pipe", "r"),  // 标准输入
			1 => array("pipe", "w"),  // 标准输出，我们将从这里读取版本信息
			2 => array("pipe", "w"),  // 标准错误
		];

		$process = proc_open($commandName, $descriptorspec, $pipes);

		if (is_resource($process)) {
			fclose($pipes[0]);  // 关闭标准输入

			// 读取标准输出，这里应该包含 npm 的版本号
			$npmVersion = stream_get_contents($pipes[1]);
			fclose($pipes[1]);

			// 关闭标准错误，如果需要的话，你也可以读取它
			fclose($pipes[2]);

			// 等待子进程结束
			proc_close($process);

			// 打印 npm 版本号
			return trim($npmVersion);
		} else {
			return false;
		}
	}
}