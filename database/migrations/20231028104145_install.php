<?php

use Phinx\Db\Adapter\MysqlAdapter;
use think\migration\Migrator;

class Install extends Migrator
{
    /**
     * 安装文件
     */
    public function change()
    {
        // 管理员相关
        $this->admin();
        $this->adminGroup();
        $this->adminGroupAccess();
        $this->adminLog();
        // 地区
        $this->area();
        // 附件
        $this->attachmennt();
        // 验证码
        $this->captcha();
        // 菜单
        $this->menuRule();
        // 数据安全
        $this->securityDartaRecycle();
        $this->securityDataRecycleLog();
        $this->securitySensitiveData();
        $this->securitySensitiveDataLog();
        // 测试
        $this->testBuild();
        // token
        $this->token();
        // 用户
        $this->user();
        $this->userGroup();
        $this->userMoneyLog();
        $this->userRule();
        // 开发
        $this->crudLog();
    }

    public function config(): void
    {
        if (!$this->hasTable('config')) {
            $table = $this->table('config', [
                'id' => false,
                'comment' => '系统配置',
                'row_format' => 'DYNAMIC',
                'primary_key' => 'id',
                'collation' => 'utf8mb4_unicode_ci',
            ]);

            $table
                // 相关ID
                ->addColumn('id', 'integer', ['comment' => 'ID', 'signed' => false, 'identity' => true, 'null' => false])
                // 分组
                ->addColumn('group', 'string', ['limit' => 30, 'default' => '', 'comment' => '分组', 'null' => false])
                // 变量相关
                ->addColumn('name', 'string', ['limit' => 30, 'default' => '', 'comment' => '变量名', 'null' => false])
                ->addColumn('title', 'string', ['limit' => 50, 'default' => '', 'comment' => '变量标题', 'null' => false])
                ->addColumn('tip', 'string', ['limit' => 100, 'default' => '', 'comment' => '变量描述', 'null' => false])
                ->addColumn('type', 'string', ['limit' => 30, 'default' => '', 'comment' => '变量输入组件类型', 'null' => false])
                // 内容相关
                ->addColumn('value', 'text', ['limit' => MysqlAdapter::TEXT_LONG, 'null' => true, 'default' => null, 'comment' => '变量值'])
                ->addColumn('content', 'text', ['limit' => MysqlAdapter::TEXT_LONG, 'null' => true, 'default' => null, 'comment' => '字典数据'])
                // 其他相关
                ->addColumn('rule', 'string', ['limit' => 100, 'default' => '', 'comment' => '验证规则', 'null' => false])
                ->addColumn('extend', 'string', ['limit' => 255, 'default' => '', 'comment' => '扩展属性', 'null' => false])
                ->addColumn('allow_del', 'integer', ['signed' => false, 'limit' => MysqlAdapter::INT_TINY, 'default' => 0, 'comment' => '允许删除:0=否,1=是', 'null' => false])
                ->addColumn('weigh', 'integer', ['default' => 0, 'comment' => '权重', 'null' => false])
                // 索引
                ->addIndex(['name'], ['unique' => true])
                ->create();
        }
    }

}
