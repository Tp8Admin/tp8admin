<?php
declare(strict_types=1);

namespace app\api\controller;


use app\common\controller\Api;

class Install extends Api
{
    /**
     * PHP环境基础检查
     */
    public function envCheckPhp(): void
    {
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

	    // 3. 检测imagettftext方法
	    $imagettftext = function_exists('imagettftext');
	    if (!$imagettftext) {
		    $imagettftextLink = [
			    [
				    'name' => '需要安装freeType库',
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

        // 输出
        $this->success('', [
			// 1. 检测php版本
            'php_version' => [
                'name'     => 'PHP版本',
                'describe' => $phpVersion,
                'state'    => $phpVersionCompare ? 'fail' : 'ok',
                'link'     => $phpVersionLink ?? [],
            ],
			// 2. 检测PDO扩展
			'php_pdo'            => [
				'name'     => '检测PDO扩展',
				'describe' => $phpPdo ? '已安装' : '未安装',
				'state'    => $phpPdo ? 'ok' : 'fail',
				'link'     => $phpPdoLink ?? []
			],
			// 3.
			'php_imagettftext'            => [
				'name'     => '检测imagettftext方法',
				'describe' => $imagettftext ? '已安装' : '未安装',
				'state'    => $imagettftext ? 'ok' : 'fail',
				'link'     => $imagettftextLink ?? []
			],
			// 4. 检测proc_open方法
			'php_proc'           => [
				'name'     => '检测proc_open方法',
				'describe' => $phpProc ? '允许执行' : '禁止执行',
				'state'    => $phpProc ? 'ok' : 'fail',
				'link'     => $phpProcLink ?? []
			],
        ]);
    }
}
