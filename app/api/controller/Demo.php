<?php

namespace app\api\controller;

use app\common\controller\Api;

class Demo extends Api
{
    /**
     * 接口成功的示例
     */
    public function demoSuccess()
    {
        $this->success('操作成功',
            ['id' => 1, 'name' => '张三']
        );
    }

    /**
     * 接口失败的示例
     */
    public function demoError()
    {
        $this->error('操作失败');
    }
}
