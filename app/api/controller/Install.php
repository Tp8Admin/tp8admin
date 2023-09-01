<?php
declare(strict_types=1);

namespace app\api\controller;


use app\common\controller\Api;

class Install extends Api
{
    /**
     * 环境基础检查
     */
    public function envBaseCheck(): void
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
                    'url'   => 'https://wonderful-code.gitee.io/guide/install/preparePHP.html'
                ]
            ];
        }

        // 输出
        $this->success('', [
            'php_version' => [
                'name'     => 'PHP版本',
                'describe' => $phpVersion,
                'state'    => $phpVersionCompare ? 'fail' : 'ok',
                'link'     => $phpVersionLink ?? [],
            ],
        ]);
    }
}
