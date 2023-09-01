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
            $this->error('PHP版本必须大于等于8.2.0');
        }

        // 输出
        $this->success('', [
            'php_version' => [
                'describe' => $phpVersion,
                'state'    => $phpVersionCompare ? 'fail' : 'ok',
                'link'     => $phpVersionLink ?? [],
            ],
        ]);
    }
}
