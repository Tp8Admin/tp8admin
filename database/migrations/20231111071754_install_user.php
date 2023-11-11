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

class InstallUser extends Migrator
{
    public function change(): void
    {
        // 用户相关
        $this->user(); // 会员
        $this->userGroup(); // 会员-分组表
        $this->userMoneyLog(); // 会员-余额变动表
        $this->userRule(); // 会员-菜单权限规则表
    }

    // +----------------------------------------------------------------------
    // | 用户相关
    // +----------------------------------------------------------------------
    private function user(): void
    {
        if (!$this->hasTable('user')) {
            $table = $this->table('user', [
                'id' => false,
                'comment' => '会员',
                'row_format' => 'DYNAMIC',
                'primary_key' => 'id',
                'collation' => 'utf8mb4_unicode_ci',
            ]);
            $table
                // 相关ID
                ->addColumn('id', 'integer', ['comment' => 'ID', 'signed' => false, 'identity' => true, 'null' => false])
                ->addColumn('group_id', 'integer', ['comment' => '分组ID', 'default' => 0, 'signed' => false, 'null' => false])
                // 基本信息
                ->addColumn('username', 'string', ['limit' => 32, 'default' => '', 'comment' => '用户名', 'null' => false])
                ->addColumn('nickname', 'string', ['limit' => 50, 'default' => '', 'comment' => '昵称', 'null' => false])
                ->addColumn('email', 'string', ['limit' => 50, 'default' => '', 'comment' => '邮箱', 'null' => false])
                ->addColumn('mobile', 'string', ['limit' => 11, 'default' => '', 'comment' => '手机', 'null' => false])
                ->addColumn('avatar', 'string', ['limit' => 255, 'default' => '', 'comment' => '头像', 'null' => false])
                ->addColumn('gender', 'integer', ['signed' => false, 'limit' => MysqlAdapter::INT_TINY, 'default' => 0, 'comment' => '性别:0=未知,1=男,2=女', 'null' => false])
                ->addColumn('birthday', 'date', ['null' => true, 'default' => null, 'comment' => '生日'])
                ->addColumn('motto', 'string', ['limit' => 255, 'default' => '', 'comment' => '签名', 'null' => false])
                // 金额信息
                ->addColumn('money', 'integer', ['comment' => '余额', 'default' => 0, 'signed' => false, 'null' => false])
                ->addColumn('score', 'integer', ['comment' => '积分', 'default' => 0, 'signed' => false, 'null' => false])
                // 登录信息
                ->addColumn('last_login_time', 'biginteger', ['limit' => 16, 'signed' => false, 'null' => true, 'default' => null, 'comment' => '上次登录时间'])
                ->addColumn('last_login_ip', 'string', ['limit' => 50, 'default' => '', 'comment' => '上次登录IP', 'null' => false])
                ->addColumn('login_failure', 'integer', ['signed' => false, 'limit' => MysqlAdapter::INT_TINY, 'default' => 0, 'comment' => '登录失败次数', 'null' => false])
                ->addColumn('join_ip', 'string', ['limit' => 50, 'default' => '', 'comment' => '加入IP', 'null' => false])
                ->addColumn('join_time', 'biginteger', ['limit' => 16, 'signed' => false, 'null' => true, 'default' => null, 'comment' => '加入时间'])
                // 安全信息
                ->addColumn('password', 'string', ['limit' => 32, 'default' => '', 'comment' => '密码', 'null' => false])
                ->addColumn('salt', 'string', ['limit' => 30, 'default' => '', 'comment' => '密码盐', 'null' => false])
                // 状态信息
                ->addColumn('status', 'string', ['limit' => 30, 'default' => '', 'comment' => '状态', 'null' => false])
                // 时间信息
                ->addColumn('create_time', 'biginteger', ['signed' => false, 'null' => true, 'default' => null, 'comment' => '创建时间'])
                ->addColumn('update_time', 'biginteger', ['signed' => false, 'null' => true, 'default' => null, 'comment' => '更新时间'])
                // 索引
                ->addIndex(['username'], [
                    'unique' => true,
                ])
                ->addIndex(['email'], [
                    'unique' => true,
                ])
                ->addIndex(['mobile'], [
                    'unique' => true,
                ])
                ->create();
        }
    }

    private function userGroup(): void
    {
        if (!$this->hasTable('user_group')) {
            $table = $this->table('user_group', [
                'id' => false,
                'comment' => '会员-分组表',
                'row_format' => 'DYNAMIC',
                'primary_key' => 'id',
                'collation' => 'utf8mb4_unicode_ci',
            ]);
            $table
                // 相关ID
                ->addColumn('id', 'integer', ['comment' => 'ID', 'signed' => false, 'identity' => true, 'null' => false])
                // 基本信息
                ->addColumn('name', 'string', ['limit' => 50, 'default' => '', 'comment' => '组名', 'null' => false])
                ->addColumn('rules', 'text', ['null' => true, 'default' => null, 'comment' => '权限节点'])
                // 状态信息
                ->addColumn('status', 'enum', ['values' => '0,1', 'default' => '1', 'comment' => '状态:0=禁用,1=启用', 'null' => false])
                // 时间信息
                ->addColumn('create_time', 'biginteger', ['signed' => false, 'null' => true, 'default' => null, 'comment' => '创建时间'])
                ->addColumn('update_time', 'biginteger', ['signed' => false, 'null' => true, 'default' => null, 'comment' => '更新时间'])
                ->create();
        }
    }

    private function userMoneyLog(): void
    {
        if (!$this->hasTable('user_money_log')) {
            $table = $this->table('user_money_log', [
                'id' => false,
                'comment' => '会员-余额变动表',
                'row_format' => 'DYNAMIC',
                'primary_key' => 'id',
                'collation' => 'utf8mb4_unicode_ci',
            ]);
            $table
                // 相关ID
                ->addColumn('id', 'integer', ['comment' => 'ID', 'signed' => false, 'identity' => true, 'null' => false])
                ->addColumn('user_id', 'integer', ['comment' => '会员ID', 'default' => 0, 'signed' => false, 'null' => false])
                // 金额信息
                ->addColumn('money', 'integer', ['comment' => '变更余额', 'default' => 0, 'null' => false])
                ->addColumn('before', 'integer', ['comment' => '变更前余额', 'default' => 0, 'null' => false])
                ->addColumn('after', 'integer', ['comment' => '变更后余额', 'default' => 0, 'null' => false])
                ->addColumn('memo', 'string', ['limit' => 255, 'default' => '', 'comment' => '备注', 'null' => false])
                // 时间信息
                ->addColumn('create_time', 'biginteger', ['signed' => false, 'null' => true, 'default' => null, 'comment' => '创建时间'])
                ->create();
        }
    }

    private function userRule(): void
    {
        if (!$this->hasTable('user_rule')) {
            $table = $this->table('user_rule', [
                'id' => false,
                'comment' => '会员-菜单权限规则表',
                'row_format' => 'DYNAMIC',
                'primary_key' => 'id',
                'collation' => 'utf8mb4_unicode_ci',
            ]);
            $table
                // 相关ID
                ->addColumn('id', 'integer', ['comment' => 'ID', 'signed' => false, 'identity' => true, 'null' => false])
                ->addColumn('pid', 'integer', ['comment' => '上级菜单', 'default' => 0, 'signed' => false, 'null' => false])
                // 基本权限规则
                ->addColumn('type', 'enum', ['values' => 'route,menu_dir,menu,nav_user_menu,nav,button', 'default' => 'menu', 'comment' => '类型:route=路由,menu_dir=菜单目录,menu=菜单项,nav_user_menu=顶栏会员菜单下拉项,nav=顶栏菜单项,button=页面按钮', 'null' => false])
                ->addColumn('title', 'string', ['limit' => 50, 'default' => '', 'comment' => '标题', 'null' => false])
                ->addColumn('name', 'string', ['limit' => 50, 'default' => '', 'comment' => '规则名称', 'null' => false])
                ->addColumn('path', 'string', ['limit' => 100, 'default' => '', 'comment' => '路由路径', 'null' => false])
                ->addColumn('icon', 'string', ['limit' => 50, 'default' => '', 'comment' => '图标', 'null' => false])
                ->addColumn('menu_type', 'enum', ['values' => 'tab,link,iframe', 'default' => 'tab', 'comment' => '菜单类型:tab=选项卡,link=链接,iframe=Iframe', 'null' => false])
                ->addColumn('url', 'string', ['limit' => 255, 'default' => '', 'comment' => '链接地址', 'null' => false])
                ->addColumn('component', 'string', ['limit' => 100, 'default' => '', 'comment' => '组件路径', 'null' => false])
                ->addColumn('no_login_valid', 'integer', ['signed' => false, 'limit' => MysqlAdapter::INT_TINY, 'default' => 0, 'comment' => '未登录有效:0=否,1=是', 'null' => false])
                ->addColumn('extend', 'enum', ['values' => 'none,add_rules_only,add_menu_only', 'default' => 'none', 'comment' => '扩展属性:none=无,add_rules_only=只添加为路由,add_menu_only=只添加为菜单', 'null' => false])
                ->addColumn('remark', 'string', ['limit' => 255, 'default' => '', 'comment' => '备注', 'null' => false])
                ->addColumn('weigh', 'integer', ['comment' => '权重', 'default' => 0, 'null' => false])
                // 状态
                ->addColumn('status', 'enum', ['values' => '0,1', 'default' => '1', 'comment' => '状态:0=禁用,1=启用', 'null' => false])
                // 相关时间
                ->addColumn('create_time', 'biginteger', ['signed' => false, 'null' => true, 'default' => null, 'comment' => '创建时间'])
                ->addColumn('update_time', 'biginteger', ['signed' => false, 'null' => true, 'default' => null, 'comment' => '更新时间'])
                // 索引
                ->addIndex(['pid'], [
                    'type' => 'BTREE',
                ])
                ->create();
        }
    }
}
