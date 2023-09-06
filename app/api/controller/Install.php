<?php
declare(strict_types=1);

namespace app\api\controller;


use app\common\controller\Api;
use ta\FileSystem;

class Install extends Api
{
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
        $phpVersionCompare = version_compare($phpVersion, '8.2.0', '<');
        if ($phpVersionCompare) {
            $phpVersionLink = [
                [
                    // 需要PHP版本
                    'name' => '需要 >= 8.0.2',
                    'type' => 'text'
                ],
                [
                    // 如何解决
                    'name'  => '如何解决',
                    'title' => '点击查看如何解决？',
                    'type'  => 'faq',
                    'url'   => '' //todo
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
                    'url'   => '' //todo
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
                    'url'   => '' //todo
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
                    'url'   => '' //todo
                ],
                [
                    'name'  => '如何修改',
                    'title' => '单击查看如何修改',
                    'type'  => 'faq',
                    'url'   => '' //todo
                ],
                [
                    'name'  => '安全保证？',
                    'title' => '正确使用安装服务不会导致任何潜在的安全问题。单击查看详细信息!',
                    'type'  => 'faq',
                    'url'   => '' //todo
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
                    'url'   => '' //todo
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
                'state'    => $phpVersionCompare ? 'fail' : 'ok',
                'link'     => $phpVersionLink ?? [],
            ],
            // 2. 检测PDO扩展
            'php_pdo'             => [
                'name'     => 'PHP相关：检测PDO扩展',
                'describe' => $phpPdo ? '已安装' : '未安装',
                'state'    => $phpPdo ? 'ok' : 'fail',
                'link'     => $phpPdoLink ?? []
            ],
            // 3. 检测gd2扩展
            'php_gd2'             => [
                'name'     => 'PHP相关：检测gd2方法',
                'describe' => $phpGd2 ? '已安装' : '未安装',
                'state'    => $phpGd2 ? 'ok' : 'fail',
                'link'     => $phpGd2Link ?? []
            ],
            // 4. 检测proc_open方法
            'php_proc'            => [
                'name'     => 'PHP相关：检测proc_open方法',
                'describe' => $phpProc ? '允许执行' : '禁止执行',
                'state'    => $phpProc ? 'ok' : 'fail',
                'link'     => $phpProcLink ?? []
            ],
            /**
             * step2: 检测目录是否可写
             */
            'runtime_is_writable' => [
                'name'     => '目录是否可写：runtime',
                'describe' => self::writableStateDescribe($runtimeIsWritable),
                'state'    => $runtimeIsWritable ? 'ok' : 'fail',
                'link'     => $runtimeIsWritableLink ?? []
            ],
        ]);
    }

	/**
	 * npm环境检查
	 */
	public function envNpmCheck()
	{
		if ($this->isInstallComplete()) {
			$this->error('', [], 2);
		}

		$packageManager = request()->post('manager', 'none');

		// npm
		$npmVersion        = Version::getVersion('npm');
		$npmVersionCompare = Version::compare(self::$needDependentVersion['npm'], $npmVersion);
		if (!$npmVersionCompare || !$npmVersion) {
			$npmVersionLink = [
				[
					// 需要版本
					'name' => __('need') . ' >= ' . self::$needDependentVersion['npm'],
					'type' => 'text'
				],
				[
					// 如何解决
					'name'  => __('How to solve?'),
					'title' => __('Click to see how to solve it'),
					'type'  => 'faq',
					'url'   => 'https://wonderful-code.gitee.io/guide/install/prepareNpm.html'
				]
			];
		}

		// 包管理器
		if (in_array($packageManager, ['npm', 'cnpm', 'pnpm', 'yarn'])) {
			$pmVersion        = Version::getVersion($packageManager);
			$pmVersionCompare = Version::compare(self::$needDependentVersion[$packageManager], $pmVersion);

			if (!$pmVersion) {
				// 安装
				$pmVersionLink[] = [
					// 需要版本
					'name' => __('need') . ' >= ' . self::$needDependentVersion[$packageManager],
					'type' => 'text'
				];
				if ($npmVersionCompare) {
					$pmVersionLink[] = [
						// 点击安装
						'name'  => __('Click Install %s', [$packageManager]),
						'title' => '',
						'type'  => 'install-package-manager'
					];
				} else {
					$pmVersionLink[] = [
						// 请先安装npm
						'name' => __('Please install NPM first'),
						'type' => 'text'
					];
				}
			} elseif (!$pmVersionCompare) {
				// 版本不足
				$pmVersionLink[] = [
					// 需要版本
					'name' => __('need') . ' >= ' . self::$needDependentVersion[$packageManager],
					'type' => 'text'
				];
				$pmVersionLink[] = [
					// 请升级
					'name' => __('Please upgrade %s version', [$packageManager]),
					'type' => 'text'
				];
			}
		} elseif ($packageManager == 'ni') {
			$pmVersion        = __('nothing');
			$pmVersionCompare = true;
		} else {
			$pmVersion        = __('nothing');
			$pmVersionCompare = false;
		}

		// nodejs
		$nodejsVersion        = Version::getVersion('node');
		$nodejsVersionCompare = Version::compare(self::$needDependentVersion['node'], $nodejsVersion);
		if (!$nodejsVersionCompare || !$nodejsVersion) {
			$nodejsVersionLink = [
				[
					// 需要版本
					'name' => __('need') . ' >= ' . self::$needDependentVersion['node'],
					'type' => 'text'
				],
				[
					// 如何解决
					'name'  => __('How to solve?'),
					'title' => __('Click to see how to solve it'),
					'type'  => 'faq',
					'url'   => 'https://wonderful-code.gitee.io/guide/install/prepareNodeJs.html'
				]
			];
		}

		$this->success('', [
			'npm_version'         => [
				'describe' => $npmVersion ?: __('Acquisition failed'),
				'state'    => $npmVersionCompare ? self::$ok : self::$warn,
				'link'     => $npmVersionLink ?? [],
			],
			'nodejs_version'      => [
				'describe' => $nodejsVersion ?: __('Acquisition failed'),
				'state'    => $nodejsVersionCompare ? self::$ok : self::$warn,
				'link'     => $nodejsVersionLink ?? []
			],
			'npm_package_manager' => [
				'describe' => $pmVersion ?: __('Acquisition failed'),
				'state'    => $pmVersionCompare ? self::$ok : self::$warn,
				'link'     => $pmVersionLink ?? [],
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
