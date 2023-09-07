<?php
declare(strict_types=1);

namespace app\api\controller;


use app\common\controller\Api;
use ta\FileSystem;
use ta\Terminal;

class Install extends Api
{
	// 版本依赖的版本，统一管理
	public static array $needDependentVersion = [
		// php
		'php'  => '8.2.3',
		// node相关
		'node' => '18.14.0',
		'npm'  => '9.3.1',
		// 包管理器
		'cnpm' => '7.1.0',
		'yarn' => '1.2.0',
		'pnpm' => '8.6.9',
	];

	// 结果的状态
	public static string $ok   = 'ok';
	public static string $warn = 'warn';
	public static string $fail = 'fail';

	/**
	 * PHP环境基础检查
	 */
	public function envCheckPhp(): void
	{
		/**
		 * step1：检测PHP环境
		 */
		// 1. 检测php版本
		$phpVersion        = phpversion();
		$phpVersionCompare = version_compare($phpVersion, '8.2.0', '>=');
		// 不符合版本返回
		if ($phpVersionCompare) {
			$phpVersionLink = [
				[
					// 需要PHP版本
					'name' => '需要 >= '. self::$needDependentVersion['php'],
					'type' => 'text'
				],
				[
					// 如何解决
					'name'  => '如何解决',
					'title' => '点击查看如何解决？',
					'type'  => 'faq',
					'url'   => '#'
				]
			];
		}

		// 2. 检测PDO扩展
		$phpPdo = extension_loaded("PDO");
		if (!$phpPdo) {
			$phpPdoLink = [
				[
					'name' => '需要安装PDO扩展',
					'type' => 'text'
				],
				[
					'name'  => '如何解决',
					'title' => '点击查看如何解决？',
					'type'  => 'faq',
					'url'   => '#'
				]
			];
		}

		// 3. 检测gd2方法
		$phpGd2 = extension_loaded('gd') && function_exists('imagettftext');
		if (!$phpGd2) {
			$phpGd2Link = [
				[
					'name' => '需要安装gd2库',
					'type' => 'text'
				],
				[
					'name'  => '如何解决',
					'title' => '点击查看如何解决？',
					'type'  => 'faq',
					'url'   => '#'
				]
			];
		}

		// 4. 检测proc_open方法
		$phpProc = function_exists('proc_open') && function_exists('proc_close') && function_exists('proc_get_status');
		if (!$phpProc) {
			$phpProcLink = [
				[
					'name'  => '查看原因',
					'title' => 'PHP Ini中的proc_open或proc_close函数被禁用',
					'type'  => 'faq',
					'url'   => '#'
				],
				[
					'name'  => '如何修改',
					'title' => '单击查看如何修改',
					'type'  => 'faq',
					'url'   => '#'
				],
				[
					'name'  => '安全保证？',
					'title' => '正确使用安装服务不会导致任何潜在的安全问题。单击查看详细信息!',
					'type'  => 'faq',
					'url'   => '#'
				],
			];
		}

		/**
		 * step2: 检测目录是否可写
		 */
		$runtimeIsWritable = FileSystem::pathIsWritable(runtime_path());
		if (!$runtimeIsWritable) {
			$runtimeIsWritableLink = [
				[
					'name'  => '查看原因',
					'title' => '单击查看原因',
					'type'  => 'faq',
					'url'   => '#'
				]
			];
		}

		// 输出
		$this->success('', [
			/**
			 * step1：检测PHP环境
			 */
			// 1. 检测php版本
			'php_version'         => [
				'name'     => 'PHP相关：版本',
				'describe' => $phpVersion,
				'state'    =>$phpVersionCompare ? self::$ok : self::$fail,
				'link'     => $phpVersionLink ?? [],
			],
			// 2. 检测PDO扩展
			'php_pdo'             => [
				'name'     => 'PHP相关：检测PDO扩展',
				'describe' => $phpPdo ? '已安装' : '未安装',
				'state'    => $phpPdo ? self::$ok : self::$fail,
				'link'     => $phpPdoLink ?? []
			],
			// 3. 检测gd2扩展
			'php_gd2'             => [
				'name'     => 'PHP相关：检测gd2方法',
				'describe' => $phpGd2 ? '已安装' : '未安装',
				'state'    => $phpGd2 ? self::$ok : self::$fail,
				'link'     => $phpGd2Link ?? []
			],
			// 4. 检测proc_open方法
			'php_proc'            => [
				'name'     => 'PHP相关：检测proc_open方法',
				'describe' => $phpProc ? '允许执行' : '禁止执行',
				'state'    => $phpProc ? self::$ok : self::$fail,
				'link'     => $phpProcLink ?? []
			],
			/**
			 * step2: 检测目录是否可写
			 */
			'runtime_is_writable' => [
				'name'     => '目录是否可写：runtime',
				'describe' => self::writableStateDescribe($runtimeIsWritable),
				'state'    => $runtimeIsWritable ? self::$ok : self::$fail,
				'link'     => $runtimeIsWritableLink ?? []
			],
		]);
	}

	/**
	 * npm环境检查
	 */
	public function envCheckNpm(): void
	{
		// 1. 检测npm版本
		$npmVersion = Terminal::getResultFromProc('npm -v');
		// 版本比较
		$npmVersionCompare = version_compare($npmVersion, self::$needDependentVersion['npm'], '>=');
		// 不符合版本返回
		if (!$npmVersionCompare) {
			$npmVersionLink = [
				[
					// 需要版本
					'name' => '需要 >= ' . self::$needDependentVersion['npm'],
					'type' => 'text'
				],
				[
					// 如何解决
					'name'  => '如何解决',
					'title' => '点击查看如何解决？',
					'type'  => 'faq',
					'url'   => '#'
				]
			];
		}

		// 2. 检测 nodejs 版本
		$nodejsVersion = Terminal::getResultFromProc('node -v');
		// 去掉版本号前面的v
		$nodejsVersion = ltrim($nodejsVersion, 'v');
		// 版本比较
		$nodejsVersionCompare = version_compare($nodejsVersion, self::$needDependentVersion['node'], '>=');
		// 不符合版本返回
		if (!$nodejsVersionCompare) {
			$nodejsVersionLink = [
				[
					// 需要版本
					'name' => '需要 >= ' . self::$needDependentVersion['node'],
					'type' => 'text'
				],
				[
					// 如何解决
					'name'  => '如何解决',
					'title' => '点击查看如何解决？',
					'type'  => 'faq',
					'url'   => '#'
				]
			];
		}

		// 3. 检测包管理器的版本
		$packageManagerName = request()->post('manager', 'none');
		if (in_array($packageManagerName, ['npm', 'yarn', 'cnpm', 'pnpm'])) {
			$packageManagerVersion = Terminal::getResultFromProc($packageManagerName . ' -v');

			// 版本比较，没有版本
			if (!$packageManagerVersion) {
				// 安装
				$packageManagerVersionLink[] = [
					// 点击安装
					'name'  => '点击安装 ' . $packageManagerName,
					'title' => '',
					'type'  => 'install-package-manager'
				];
			}

			// 正常版本，版本比较
			$packageManagerVersionCompare = version_compare($packageManagerVersion, self::$needDependentVersion[$packageManagerName], '>=');
			if (!$packageManagerVersionCompare) {
				// 版本不足,需要版本
				$packageManagerVersionLink[] = [
					'name' => '需要 >=' . self::$needDependentVersion[$packageManagerName],
					'type' => 'text'
				];
				// 请升级
				$packageManagerVersionLink[] = [
					'name' => '请升级', [$packageManagerName],
					'type' => 'text'
				];
			}
		}

		// 返回结果
		$this->success('获取成功', [
			'npm_version'     => [
				'name'     => '前端相关：NPM版本',
				'describe' => $npmVersion ?: '获取失败',
				'state'    => $npmVersionCompare ? self::$ok : self::$warn,
				'link'     => $npmVersionLink ?? [],
			],
			'nodejs_version'  => [
				'name'     => '前端相关：NodeJS版本',
				'describe' => $nodejsVersion ?: '获取失败',
				'state'    => $nodejsVersionCompare ? self::$ok : self::$warn,
				'link'     => $nodejsVersionLink ?? []
			],
			'package_manager' => [
				'name'     => '前端相关：包管理器' . $packageManagerName,
				'describe' => $packageManagerName ?: '获取失败',
				'state'    => $packageManagerVersionCompare ? self::$ok : self::$warn,
				'link'     => $packageManagerVersionLink ?? [],
			]
		]);
	}

	/**
	 * 目录是否可写描述
	 * @param bool $state
	 * @return string
	 */
	public function writableStateDescribe(bool $state): string
	{
		return $state ? '可写' : '无写权限';
	}
}
