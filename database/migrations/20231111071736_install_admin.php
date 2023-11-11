<?php
// +----------------------------------------------------------------------
// | NewThink [ Think More,Think Better! ]
// +----------------------------------------------------------------------
// | Copyright (c) 2016~2030 http://www.sxqibo.com All rights reserved.
// +----------------------------------------------------------------------
// | 版权所有：山西岐伯信息科技有限公司
// +----------------------------------------------------------------------
// | Author: yanghongwei  Date:2023/11/1 Time:16:56
// +----------------------------------------------------------------------

declare(strict_types=1);

use Phinx\Db\Adapter\MysqlAdapter;
use think\migration\Migrator;

class InstallAdmin extends Migrator
{
    public function change(): void
    {
        // 管理员相关
        $this->admin(); // 管理员
        $this->adminGroup(); // 管理员-分组表
        $this->adminGroupAccess(); // 管理员-菜单和权限规则
        $this->adminRule();// 管理员-菜单和权限规则
        $this->adminLog(); // 管理员-日志表
    }

    // +----------------------------------------------------------------------
    // | 管理员相关
    // +----------------------------------------------------------------------
    public function admin(): void
    {
        if (!$this->hasTable('admin')) {
            $table = $this->table('admin', [
                'id' => false,
                'comment' => '管理员',
                'row_format' => 'DYNAMIC',
                'primary_key' => 'id',
                'collation' => 'utf8mb4_unicode_ci',
            ]);

            $table
                // 相关ID
                ->addColumn('id', 'integer', ['comment' => 'ID', 'signed' => false, 'identity' => true, 'null' => false])
                // 基本信息
                ->addColumn('username', 'string', ['limit' => 20, 'default' => '', 'comment' => '用户名', 'null' => false])
                ->addColumn('nickname', 'string', ['limit' => 50, 'default' => '', 'comment' => '昵称', 'null' => false])
                ->addColumn('avatar', 'string', ['limit' => 255, 'default' => '', 'comment' => '头像', 'null' => false])
                ->addColumn('email', 'string', ['limit' => 50, 'default' => '', 'comment' => '邮箱', 'null' => false])
                ->addColumn('mobile', 'string', ['limit' => 11, 'default' => '', 'comment' => '手机', 'null' => false])
                ->addColumn('signature', 'string', ['limit' => 255, 'default' => '', 'comment' => '签名', 'null' => false])
                // 登录相关
                ->addColumn('login_failure', 'integer', ['signed' => false, 'limit' => MysqlAdapter::INT_TINY, 'default' => 0, 'comment' => '登录失败次数', 'null' => false])
                ->addColumn('last_login_time', 'biginteger', ['signed' => false, 'default' => 0, 'comment' => '上次登录时间', 'null' => true])
                // 权限相关
                ->addColumn('password', 'string', ['limit' => 255, 'default' => '', 'comment' => '密码', 'null' => false])
                ->addColumn('salt', 'string', ['limit' => 255, 'default' => '', 'comment' => '密码盐', 'null' => false])
                // 状态
                ->addColumn('status', 'integer', ['signed' => false, 'limit' => MysqlAdapter::INT_TINY, 'default' => 1, 'comment' => '状态:0=禁用,1=正常', 'null' => false])
                // 创建时间
                ->addColumn('create_time', 'biginteger', ['signed' => false, 'default' => 0, 'comment' => '创建时间', 'null' => false])
                // 更新时间
                ->addColumn('update_time', 'biginteger', ['signed' => false, 'default' => 0, 'comment' => '更新时间', 'null' => false])
                // 索引
                ->addIndex(['username'], ['unique' => true])
                ->addIndex(['email'], ['unique' => true])
                ->addIndex(['mobile'], ['unique' => true])
                ->create();
        }
    }

    public function adminGroup(): void
    {
        if (!$this->table('admin_group')) {
            $table = $this->table('admin_group', [
                'id' => false,
                'comment' => '管理员-分组表',
                'row_format' => 'DYNAMIC',
                'primary_key' => 'id',
                'collation' => 'utf8mb4_unicode_ci',
            ]);
            $table
                // ID相关
                ->addColumn('id', 'integer', ['comment' => 'ID', 'signed' => false, 'identity' => true, 'null' => false])
                ->addColumn('pid', 'integer', ['signed' => false, 'limit' => MysqlAdapter::INT_SMALL, 'default' => 0, 'comment' => '上级分组ID', 'null' => false])
                // 权限规则ID
                ->addColumn('rule_ids', 'string', ['limit' => 255, 'default' => '', 'comment' => '权限规则ID', 'null' => false])
                // 组名
                ->addColumn('name', 'string', ['limit' => 30, 'default' => '', 'comment' => '组名', 'null' => false])
                // 状态
                ->addColumn('status', 'integer', ['signed' => false, 'limit' => MysqlAdapter::INT_TINY, 'default' => 1, 'comment' => '状态:0=禁用,1=正常', 'null' => false])
                // 时间相关
                ->addColumn('create_time', 'biginteger', ['signed' => false, 'default' => 0, 'comment' => '创建时间', 'null' => false])
                ->addColumn('update_time', 'biginteger', ['signed' => false, 'default' => 0, 'comment' => '更新时间', 'null' => false])
                // 索引
                ->addIndex(['name'], ['unique' => true])
                ->create();
        }
    }

    public function adminGroupAccess(): void
    {
        if (!$this->hasTable('admin_group_access')) {
            $table = $this->table('admin_group_access', [
                'id' => false,
                'comment' => '管理员-分组权限关联',
                'row_format' => 'DYNAMIC',
                'primary_key' => ['admin_id', 'group_id'],
                'collation' => 'utf8mb4_unicode_ci',
            ]);

            $table
                // 相关ID
                ->addColumn('admin_id', 'integer', ['comment' => '管理员ID', 'signed' => false, 'null' => false])
                ->addColumn('group_id', 'integer', ['comment' => '分组ID', 'signed' => false, 'null' => false])
                // 索引
                ->addIndex(['admin_id', 'group_id'], ['unique' => true])
                ->create();
        }
    }

    private function adminRule(): void
    {
        if (!$this->hasTable('admin_rule')) {
            $table = $this->table('admin_rule', [
                'id' => false,
                'comment' => '管理员-菜单和权限规则',
                'row_format' => 'DYNAMIC',
                'primary_key' => 'id',
                'collation' => 'utf8mb4_unicode_ci',
            ]);

            $table
                // ID相关
                ->addColumn('id', 'integer', ['comment' => 'ID', 'signed' => false, 'identity' => true, 'null' => false])
                ->addColumn('pid', 'integer', ['signed' => false, 'default' => 0, 'comment' => '上级ID', 'null' => false])
                // 基本权限规则
                ->addColumn('type', 'enum', ['values' => 'menu_dir,menu,button', 'default' => 'menu', 'comment' => '类型：menu_dir=菜单目录,menu=菜单项,button=页面按钮', 'null' => false])
                ->addColumn('title', 'string', ['limit' => 50, 'default' => '', 'comment' => '标题', 'null' => false])
                ->addColumn('name', 'string', ['limit' => 50, 'default' => '', 'comment' => '规则名称', 'null' => false])
                ->addColumn('path', 'string', ['limit' => 255, 'default' => '', 'comment' => '路由路径', 'null' => false])
                ->addColumn('icon', 'string', ['limit' => 50, 'default' => '', 'comment' => '图标', 'null' => false])
                ->addColumn('menu_type', 'enum', ['values' => 'tab,link,iframe', 'default' => null, 'comment' => '菜单类型:tab=选项卡,link=链接,iframe=Iframe', 'null' => true])
                ->addColumn('url', 'string', ['limit' => 255, 'default' => '', 'comment' => '链接', 'null' => false])
                ->addColumn('component', 'string', ['limit' => 255, 'default' => '', 'comment' => '组件', 'null' => false])
                ->addColumn('keepalive', 'integer', ['signed' => false, 'limit' => MysqlAdapter::INT_TINY, 'default' => 0, 'comment' => '缓存:0=否,1=是', 'null' => false])
                ->addColumn('extend', 'enum', ['values' => 'none,add_rules_only,add_menu_only', 'default' => 'none', 'comment' => '扩展属性:none=无,add_rules_only=只添加为路由,add_menu_only=只添加为菜单', 'null' => true])
                ->addColumn('remark', 'string', ['limit' => 255, 'default' => '', 'comment' => '备注', 'null' => false])
                ->addColumn('weigh', 'integer', ['default' => 0, 'comment' => '权重', 'null' => false])
                ->addColumn('status', 'enum', ['values' => 'normal,hidden,disabled', 'default' => 'normal', 'comment' => '状态:normal=正常,hidden=隐藏,disabled=禁用', 'null' => false])
                // 时间相关
                ->addColumn('create_time', 'biginteger', ['signed' => false, 'default' => 0, 'comment' => '创建时间', 'null' => false])
                ->addColumn('update_time', 'biginteger', ['signed' => false, 'default' => 0, 'comment' => '更新时间', 'null' => false])
                // 索引
                ->addIndex(['pid'])
                ->addIndex(['name'], ['unique' => true])
                ->create();
        }
    }

    public function adminLog(): void
    {
        if (!$this->hasTable('admin_log')) {
            $table = $this->table('admin_log', [
                'id' => false,
                'comment' => '管理员-日志表',
                'row_format' => 'DYNAMIC',
                'primary_key' => 'id',
                'collation' => 'utf8mb4_unicode_ci',
            ]);

            $table
                // 相关ID
                ->addColumn('id', 'integer', ['comment' => 'ID', 'signed' => false, 'identity' => true, 'null' => false])
                ->addColumn('admin_id', 'integer', ['comment' => '管理员ID', 'signed' => false, 'null' => false])
                // 内容相关
                ->addColumn('username', 'string', ['limit' => 20, 'default' => '', 'comment' => '用户名', 'null' => false])
                ->addColumn('url', 'string', ['limit' => 255, 'default' => '', 'comment' => '操作URL', 'null' => false])
                ->addColumn('title', 'string', ['limit' => 50, 'default' => '', 'comment' => '日志标题', 'null' => false])
                ->addColumn('data', 'text', ['limit' => MysqlAdapter::TEXT_LONG, 'null' => true, 'default' => null, 'comment' => '请求数据'])
                ->addColumn('ip', 'string', ['limit' => 15, 'default' => '', 'comment' => 'IP地址', 'null' => false])
                ->addColumn('user_agent', 'string', ['limit' => 255, 'default' => '', 'comment' => 'user-agent', 'null' => false])
                // 时间相关
                ->addColumn('create_time', 'biginteger', ['signed' => false, 'default' => 0, 'comment' => '创建时间', 'null' => false])
                // 索引
                ->addIndex(['admin_id'], ['unique' => false])
                ->create();
        }
    }
}
