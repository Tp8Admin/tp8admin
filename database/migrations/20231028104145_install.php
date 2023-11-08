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

class Install extends Migrator
{
    /**
     * 安装文件
     */
    public function change(): void
    {
        // 管理员相关
        $this->admin(); // 管理员
        $this->adminGroup(); // 管理员-分组表
        $this->adminGroupAccess(); // 管理员-菜单和权限规则
        $this->adminRule();// 管理员-菜单和权限规则
        $this->adminLog(); // 管理员-日志表
        // 通用相关
        $this->commonConfig(); // 通用-系统配置
        $this->commonArea();// 通用-地区
        $this->commonAttachment();// 通用-附件
        $this->commonCaptcha();// 通用-验证码
        $this->commonToken();// 通用-TOKEN
        // 安全相关
        $this->securityDataRecycle(); // 安全-回收规则表
        $this->securityDataRecycleLog(); // 安全-数据回收记录
        $this->securitySensitiveData(); // 安全-敏感数据规则
        $this->securitySensitiveDataLog(); // '安全-敏感数据修改记录
        // 用户相关
        $this->user(); // 会员
        $this->userGroup(); // 会员-分组表
        $this->userMoneyLog(); // 会员-余额变动表
        $this->userRule(); // 会员-菜单权限规则表
        // 开发相关
        $this->devTest1(); // 开发-测试-根据字段类型
        $this->devTest2(); // 开发-测试-特殊字段
        $this->devTest3(); // 开发-测试-以特殊字符结尾的规则
        $this->devCrudLog(); // 开发-CRUD记录表

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

    // +----------------------------------------------------------------------
    // | 通用相关
    // +----------------------------------------------------------------------

    public function commonConfig(): void
    {
        if (!$this->hasTable('common_config')) {
            $table = $this->table('config', [
                'id' => false,
                'comment' => '通用-系统配置',
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

    public function commonArea(): void
    {
        if (!$this->hasTable('common_area')) {
            $table = $this->table('area', [
                'id' => false,
                'comment' => '通用-地区',
                'row_format' => 'DYNAMIC',
                'primary_key' => 'id',
                'collation' => 'utf8mb4_unicode_ci',
            ]);

            $table
                // 相关ID
                ->addColumn('id', 'integer', ['comment' => 'ID', 'signed' => false, 'identity' => true, 'null' => false])
                ->addColumn('pid', 'integer', ['signed' => false, 'limit' => MysqlAdapter::INT_SMALL, 'default' => 0, 'comment' => '上级地区ID', 'null' => false])
                // 名称相关
                ->addColumn('name', 'string', ['limit' => 50, 'default' => '', 'comment' => '地区名称', 'null' => false])
                ->addColumn('short_name', 'string', ['limit' => 50, 'default' => '', 'comment' => '地区简称', 'null' => false])
                ->addColumn('merger_name', 'string', ['limit' => 255, 'default' => '', 'comment' => '地区全称', 'null' => false])
                // 内容相关
                ->addColumn('level', 'integer', ['signed' => false, 'limit' => MysqlAdapter::INT_TINY, 'default' => null, 'comment' => '层级:1=省,2=市,3=区/县', 'null' => false])
                ->addColumn('code', 'string', ['limit' => 20, 'default' => '', 'comment' => '地区编码', 'null' => false])
                ->addColumn('zip_code', 'string', ['limit' => 20, 'default' => '', 'comment' => '邮政编码', 'null' => false])
                ->addColumn('pinyin', 'string', ['limit' => 255, 'default' => '', 'comment' => '拼音', 'null' => false])
                ->addColumn('first', 'string', ['limit' => 50, 'default' => '', 'comment' => '首字母', 'null' => false])
                ->addColumn('lng', 'string', ['limit' => 20, 'default' => '', 'comment' => '经度', 'null' => false])
                ->addColumn('lat', 'string', ['limit' => 20, 'default' => '', 'comment' => '纬度', 'null' => false])
                // 索引
                ->addIndex(['pid'])
                ->addIndex(['code'], ['unique' => true])
                ->create();
        }
    }

    private function commonAttachment(): void
    {
        if (!$this->hasTable('common_attachment')) {
            $table = $this->table('attachment', [
                'id' => false,
                'comment' => '通用-附件',
                'row_format' => 'DYNAMIC',
                'primary_key' => 'id',
                'collation' => 'utf8mb4_unicode_ci',
            ]);

            $table
                // 相关ID
                ->addColumn('id', 'integer', ['comment' => 'ID', 'signed' => false, 'identity' => true, 'null' => false])
                ->addColumn('admin_id', 'integer', ['signed' => false, 'default' => 0, 'comment' => '管理员ID', 'null' => false])
                ->addColumn('user_id', 'integer', ['signed' => false, 'default' => 0, 'comment' => '上传用户ID', 'null' => false])
                // 名称
                ->addColumn('name', 'string', ['limit' => 50, 'default' => '', 'comment' => '名称', 'null' => false])
                // 附件相关
                ->addColumn('url', 'string', ['limit' => 255, 'default' => '', 'comment' => 'url', 'null' => false])
                ->addColumn('width', 'integer', ['signed' => false, 'limit' => MysqlAdapter::INT_SMALL, 'default' => 0, 'comment' => '宽度', 'null' => false])
                ->addColumn('height', 'integer', ['signed' => false, 'limit' => MysqlAdapter::INT_SMALL, 'default' => 0, 'comment' => '高度', 'null' => false])
                ->addColumn('size', 'integer', ['signed' => false, 'limit' => MysqlAdapter::INT_BIG, 'default' => 0, 'comment' => '大小', 'null' => false])
                ->addColumn('mime_type', 'string', ['limit' => 30, 'default' => '', 'comment' => 'mine类型', 'null' => false])
                ->addColumn('quote', 'integer', ['signed' => false, 'limit' => MysqlAdapter::INT_SMALL, 'default' => 0, 'comment' => '引用次数', 'null' => false])
                ->addColumn('storage', 'string', ['limit' => 30, 'default' => '', 'comment' => '存储引擎', 'null' => false])
                ->addColumn('sha1', 'string', ['limit' => 40, 'default' => '', 'comment' => 'sha1', 'null' => false])
                // 时间相关
                ->addColumn('create_time', 'biginteger', ['signed' => false, 'default' => 0, 'comment' => '创建时间', 'null' => false])
                ->addColumn('last_time', 'biginteger', ['signed' => false, 'default' => 0, 'comment' => '最后上传时间', 'null' => false])
                // 索引
                ->addIndex(['admin_id'])
                ->addIndex(['user_id'])
                ->addIndex(['name'])
                ->addIndex(['url'])
                ->addIndex(['sha1'])
                ->create();
        }
    }

    private function commonCaptcha(): void
    {
        if (!$this->hasTable('common_captcha')) {
            $table = $this->table('captcha', [
                'id' => false,
                'comment' => '通用-验证码',
                'row_format' => 'DYNAMIC',
                'primary_key' => 'id',
                'collation' => 'utf8mb4_unicode_ci',
            ]);

            $table
                // 验证码相关
                ->addColumn('key', 'string', ['limit' => 32, 'default' => '', 'comment' => '验证码KEY', 'null' => false])
                ->addColumn('code', 'string', ['limit' => 32, 'default' => '', 'comment' => '验证码(加密后)', 'null' => false])
                ->addColumn('captcha', 'text', ['limit' => MysqlAdapter::TEXT_LONG, 'null' => true, 'default' => null, 'comment' => '验证码数据'])
                // 时间相关
                ->addColumn('create_time', 'biginteger', ['signed' => false, 'default' => 0, 'comment' => '创建时间', 'null' => false])
                ->addColumn('expire_time', 'biginteger', ['signed' => false, 'default' => 0, 'comment' => '失效时间', 'null' => false])
                // 索引
                ->addIndex(['key'], ['unique' => true])
                ->create();
        }
    }


    private function commonToken(): void
    {
        if (!$this->hasTable('common_token')) {
            $table = $this->table('common_token', [
                'id' => false,
                'comment' => '通用-TOKEN',
                'row_format' => 'DYNAMIC',
                'primary_key' => 'id',
                'collation' => 'utf8mb4_unicode_ci',
            ]);

            $table
                // 相关内容
                ->addColumn('token', 'string', ['limit' => 50, 'default' => '', 'comment' => 'Token', 'null' => false])
                ->addColumn('type', 'string', ['limit' => 30, 'default' => '', 'comment' => '类型,如admin,user等', 'null' => false])
                ->addColumn('user_id', 'integer', ['signed' => false, 'default' => 0, 'comment' => '用户ID', 'null' => false])
                // 时间相关
                ->addColumn('expire_time', 'biginteger', ['signed' => false, 'default' => 0, 'comment' => '失效时间', 'null' => false])
                ->addColumn('create_time', 'biginteger', ['signed' => false, 'default' => 0, 'comment' => '创建时间', 'null' => false])
                // 索引
                ->addIndex(['token'], ['unique' => true])
                ->create();
        }
    }


    // +----------------------------------------------------------------------
    // | 安全相关
    // +----------------------------------------------------------------------

    private function securityDataRecycle(): void
    {
        if (!$this->hasTable('security_data_recycle')) {
            $table = $this->table('security_data_recycle', [
                'id' => false,
                'comment' => '安全-回收规则',
                'row_format' => 'DYNAMIC',
                'primary_key' => 'id',
                'collation' => 'utf8mb4_unicode_ci',
            ]);

            $table
                // 相关ID
                ->addColumn('id', 'integer', ['comment' => 'ID', 'signed' => false, 'identity' => true, 'null' => false])
                // 回收规则信息
                ->addColumn('name', 'string', ['limit' => 50, 'default' => '', 'comment' => '规则名称', 'null' => false])
                ->addColumn('controller', 'string', ['limit' => 50, 'default' => '', 'comment' => '控制器', 'null' => false])
                ->addColumn('controller_name', 'string', ['limit' => 50, 'default' => '', 'comment' => '控制器名', 'null' => false])
                ->addColumn('data_table', 'string', ['limit' => 50, 'default' => '', 'comment' => '对应数据表', 'null' => false])
                ->addColumn('primary_key', 'string', ['limit' => 50, 'default' => '', 'comment' => '数据表主键', 'null' => false])
                ->addColumn('status', 'enum', ['values' => '0,1', 'default' => '1', 'comment' => '状态:0=禁用,1=启用', 'null' => false])
                // 时间相关
                ->addColumn('create_time', 'biginteger', ['signed' => false, 'default' => 0, 'comment' => '创建时间', 'null' => false])
                ->addColumn('update_time', 'biginteger', ['signed' => false, 'default' => 0, 'comment' => '更新时间', 'null' => false])
                // 索引
                ->addIndex(['name'])
                ->addIndex(['controller'])
                ->addIndex(['controller_name'])
                ->addIndex(['data_table'])
                ->addIndex(['primary_key'])
                ->addIndex(['status'])
                ->create();
        }
    }

    private function securityDataRecycleLog(): void
    {
        if (!$this->hasTable('security_data_recycle_log')) {
            $table = $this->table('security_data_recycle_log', [
                'id' => false,
                'comment' => '安全-数据回收记录',
                'row_format' => 'DYNAMIC',
                'primary_key' => 'id',
                'collation' => 'utf8mb4_unicode_ci',
            ]);
            $table
                // 相关ID
                ->addColumn('id', 'integer', ['comment' => 'ID', 'signed' => false, 'identity' => true, 'null' => false])
                ->addColumn('admin_id', 'integer', ['comment' => '操作管理员', 'default' => 0, 'signed' => false, 'null' => false])
                ->addColumn('recycle_id', 'integer', ['comment' => '回收规则ID', 'default' => 0, 'signed' => false, 'null' => false])
                // 回收数据
                ->addColumn('data', 'text', ['null' => true, 'default' => null, 'comment' => '回收的数据'])
                ->addColumn('data_table', 'string', ['limit' => 100, 'default' => '', 'comment' => '数据表', 'null' => false])
                ->addColumn('primary_key', 'string', ['limit' => 50, 'default' => '', 'comment' => '数据表主键', 'null' => false])
                ->addColumn('is_restore', 'integer', ['signed' => false, 'limit' => MysqlAdapter::INT_TINY, 'default' => 0, 'comment' => '是否已还原:0=否,1=是', 'null' => false])
                ->addColumn('ip', 'string', ['limit' => 50, 'default' => '', 'comment' => '操作者IP', 'null' => false])
                ->addColumn('useragent', 'string', ['limit' => 255, 'default' => '', 'comment' => 'User-Agent', 'null' => false])
                // 相关时间
                ->addColumn('create_time', 'biginteger', ['signed' => false, 'null' => true, 'default' => null, 'comment' => '创建时间'])
                ->create();
        }
    }

    private function securitySensitiveData(): void
    {
        if (!$this->hasTable('security_sensitive_data')) {
            $table = $this->table('security_sensitive_data', [
                'id' => false,
                'comment' => '安全-敏感数据规则',
                'row_format' => 'DYNAMIC',
                'primary_key' => 'id',
                'collation' => 'utf8mb4_unicode_ci',
            ]);
            $table
                ->addColumn('id', 'integer', ['comment' => 'ID', 'signed' => false, 'identity' => true, 'null' => false])
                ->addColumn('name', 'string', ['limit' => 50, 'default' => '', 'comment' => '规则名称', 'null' => false])
                // 敏感规则信息
                ->addColumn('controller', 'string', ['limit' => 100, 'default' => '', 'comment' => '控制器', 'null' => false])
                ->addColumn('controller_as', 'string', ['limit' => 100, 'default' => '', 'comment' => '控制器别名', 'null' => false])
                ->addColumn('data_table', 'string', ['limit' => 100, 'default' => '', 'comment' => '对应数据表', 'null' => false])
                ->addColumn('primary_key', 'string', ['limit' => 50, 'default' => '', 'comment' => '数据表主键', 'null' => false])
                ->addColumn('data_fields', 'text', ['null' => true, 'default' => null, 'comment' => '敏感数据字段'])
                // 状态
                ->addColumn('status', 'enum', ['values' => '0,1', 'default' => '1', 'comment' => '状态:0=禁用,1=启用', 'null' => false])
                // 相关时间
                ->addColumn('create_time', 'biginteger', ['signed' => false, 'null' => true, 'default' => null, 'comment' => '创建时间'])
                ->addColumn('update_time', 'biginteger', ['signed' => false, 'null' => true, 'default' => null, 'comment' => '更新时间'])
                ->create();
        }
    }

    private function securitySensitiveDataLog(): void
    {
        if (!$this->hasTable('security_sensitive_data_log')) {
            $table = $this->table('security_sensitive_data_log', [
                'id' => false,
                'comment' => '安全-敏感数据修改记录',
                'row_format' => 'DYNAMIC',
                'primary_key' => 'id',
                'collation' => 'utf8mb4_unicode_ci',
            ]);
            $table
                // 相关ID
                ->addColumn('id', 'integer', ['comment' => 'ID', 'signed' => false, 'identity' => true, 'null' => false])
                ->addColumn('admin_id', 'integer', ['comment' => '操作管理员', 'default' => 0, 'signed' => false, 'null' => false])
                ->addColumn('sensitive_id', 'integer', ['comment' => '敏感数据规则ID', 'default' => 0, 'signed' => false, 'null' => false])
                // 敏感数据修改记录信息
                ->addColumn('data_table', 'string', ['limit' => 100, 'default' => '', 'comment' => '数据表', 'null' => false])
                ->addColumn('primary_key', 'string', ['limit' => 50, 'default' => '', 'comment' => '数据表主键', 'null' => false])
                ->addColumn('data_field', 'string', ['limit' => 50, 'default' => '', 'comment' => '被修改字段', 'null' => false])
                ->addColumn('data_comment', 'string', ['limit' => 50, 'default' => '', 'comment' => '被修改项', 'null' => false])
                ->addColumn('id_value', 'integer', ['comment' => '被修改项主键值', 'default' => 0, 'null' => false])
                // 修改前后
                ->addColumn('before', 'text', ['null' => true, 'default' => null, 'comment' => '修改前'])
                ->addColumn('after', 'text', ['null' => true, 'default' => null, 'comment' => '修改后'])
                // 其他信息
                ->addColumn('ip', 'string', ['limit' => 50, 'default' => '', 'comment' => '操作者IP', 'null' => false])
                ->addColumn('useragent', 'string', ['limit' => 255, 'default' => '', 'comment' => 'User-Agent', 'null' => false])
                ->addColumn('is_rollback', 'integer', ['signed' => false, 'limit' => MysqlAdapter::INT_TINY, 'default' => 0, 'comment' => '是否已回滚:0=否,1=是', 'null' => false])
                // 相关时间
                ->addColumn('create_time', 'biginteger', ['signed' => false, 'null' => true, 'default' => null, 'comment' => '创建时间'])
                ->create();
        }
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

    // +----------------------------------------------------------------------
    // | 开发相关
    // +----------------------------------------------------------------------


    private function devTest1(): void
    {
        if (!$this->hasTable('dev_test1')) {
            $table = $this->table('dev_test1', [
                'id' => false,
                'comment' => '开发-测试-根据字段类型',
                'row_format' => 'DYNAMIC',
                'primary_key' => 'id',
                'collation' => 'utf8mb4_unicode_ci',
            ]);

            $table
                // 1. int， 整型， 自动生成type为number的文本框，步长为1
                ->addColumn('user_id', 'integer', ['default' => 0, 'comment' => '会员ID'])
                // 2. enum， 枚举型， 自动生成单选下拉列表框
                ->addColumn('status', 'enum', ['values' => ['normal', 'hidden'], 'default' => 'normal', 'comment' => '状态'])
                // 3. set，set型， 自动生成多选下拉列表框
                ->addColumn('flag', 'set', ['values' => ['hot', 'index', 'recommend'], 'default' => '', 'comment' => '标志(多选):hot=热门,index=首页,recommend=推荐'])
                // 4. float    浮点型    自动生成type为number的文本框，步长根据小数点位数生成
                // 5. text    文本型    自动生成textarea文本框
                ->addColumn('content', 'text', ['comment' => '内容'])
                // 6. datetime    日期时间    自动生成日期时间的组件
                ->addColumn('activity_time', 'datetime', ['null' => true, 'comment' => '活动时间(datetime)'])
                // 7. date    日期型    自动生成日期型的组件
                ->addColumn('start_date', 'date', ['null' => true, 'comment' => '开始日期'])
                // 8. timestamp    时间戳    自动生成日期时间的组件
                // 9. varchar    字符串    当字符串长度定义大于等于255时，将自动在列表启用 auto_content
                ->addColumn('user_id', 'string', ['default' => 0, 'comment' => '会员ID'])
                ->create();

        }

    }

    private function devTest2(): void
    {
        if (!$this->hasTable('dev_test2')) {
            $table = $this->table('dev_test2', [
                'id' => false,
                'comment' => '开发-测试-特殊字段',
                'row_format' => 'DYNAMIC',
                'primary_key' => 'id',
                'collation' => 'utf8mb4_unicode_ci',
            ]);

            $table
                // 1. 特殊字段: user_id, 会员ID, int, 将生成选择会员的SelectPage组件，单选
                ->addColumn('user_id', 'integer', ['default' => 0, 'comment' => '会员ID'])
                // 2. 特殊字段: user_ids, 会员ID集合, varchar, 将生成选择会员的SelectPage组件，多选
                ->addColumn('user_id', 'string', ['default' => 0, 'comment' => '会员ID'])
                // 3. 特殊字段: admin_id, 管理员ID, int, 将生成选择管理员的SelectPage组件
                ->addColumn('admin_id', 'integer', ['default' => 0, 'comment' => '管理员ID'])
                // 4. 特殊字段: admin_ids, 管理员ID集合, varchar, 将生成选择管理员的SelectPage组件，多选
                ->addColumn('admin_ids', 'string', ['default' => 0, 'comment' => '会员ID'])
                // 5. 特殊字段： category_id， 分类ID， int， 将生成选择分类的下拉框，分类类型根据去掉前缀的表名，单选
                ->addColumn('category_id', 'integer', ['default' => 0, 'unsigned' => true, 'comment' => '分类ID(单选)'])
                // 6. 特殊字段： category_ids， 分类ID集合， varchar， 将生成选择分类的下拉框，分类类型根据去掉前缀的表名，多选
                ->addColumn('category_ids', 'string', ['limit' => 100, 'null' => true, 'comment' => '分类ID(多选)'])
                // 7. 特殊字段： weigh，权重，int，后台的排序字段，如果存在该字段将出现排序按钮，可上下拖动进行排序
                ->addColumn('weigh', 'integer', ['default' => 0, 'comment' => '权重'])
                // 8. 特殊字段： 创建时间， bigint/datetime， 记录添加时间字段，不需要手动维护
                ->addColumn('create_time', 'biginteger', ['null' => true, 'comment' => '创建时间'])
                // 9. 特殊字段： 更新时间， bigint/datetime， 记录更新时间的字段，不需要手动维护
                ->addColumn('update_time', 'biginteger', ['null' => true, 'comment' => '更新时间'])
                // 10. 特殊字段： 删除时间， bigint/datetime， 记录删除时间的字段，不需要手动维护，如果存在此字段将会生成回收站功能，字段默认值务必为null
                ->addColumn('delete_time', 'biginteger', ['null' => true, 'comment' => '删除时间'])
                // 11. 特殊字段： 状态字段， enum， 列表筛选字段，如果存在此字段将启用TAB选项卡展示列表
                ->addColumn('status', 'enum', ['values' => ['normal', 'hidden'], 'default' => 'normal', 'comment' => '状态'])
                ->create();

        }

    }

    private function devTest3(): void
    {
        if (!$this->hasTable('dev_test1')) {
            $table = $this->table('dev_test1', [
                'id' => false,
                'comment' => '开发-测试-以特殊字符结尾的规则',
                'row_format' => 'DYNAMIC',
                'primary_key' => 'id',
                'collation' => 'utf8mb4_unicode_ci',
            ]);

            $table
                // 1. time, activity_time, bigint/datetime, 识别为日期时间型数据，自动创建选择时间的组件
                ->addColumn('activity1_time', 'biginteger', ['null' => true, 'comment' => '活动时间(bigint)'])
                ->addColumn('activity2_time', 'datetime', ['null' => true, 'comment' => '活动时间(datetime)'])

                // 2. 以特殊字符结尾的规则: image, small_image, varchar, 识别为图片文件，自动生成可上传图片的组件，单图
                ->addColumn('image', 'string', ['limit' => 100, 'default' => '', 'comment' => '图片'])

                // 3. 以特殊字符结尾的规则: images, small_images, varchar, 识别为图片文件, 自动生成可上传图片的组件，多图
                ->addColumn('images', 'string', ['limit' => 1500, 'default' => '', 'comment' => '图片组'])

                // 4. 以特殊字符结尾的规则: file, attach_file, varchar, 识别为普通文件，自动生成可上传文件的组件，单文件
                ->addColumn('attach_file', 'string', ['limit' => 100, 'default' => '', 'comment' => '附件'])

                // 5. 以特殊字符结尾的规则: files, attach_files, varchar, 识别为普通文件，自动生成可上传文件的组件，多文件
                ->addColumn('attach_files', 'string', ['limit' => 500, 'default' => '', 'comment' => '附件'])

                // 6. 以特殊字符结尾的规则: avatar, mini_avatar, varchar, 识别为头像，自动生成可上传图片的组件，单图
                ->addColumn('mini_avatar', 'string', ['limit' => 500, 'default' => '', 'comment' => '头像'])

                // 7. 以特殊字符结尾的规则: avatars, mini_avatars, varchar, 识别为头像，自动生成可上传图片的组件，多图
                ->addColumn('mini_avatars', 'string', ['limit' => 500, 'default' => '', 'comment' => '头像'])

                // 8. 以特殊字符结尾的规则: content, main_content, text/mediumtext/longtext, 识别为内容，自动生成富文本编辑器(需安装富文本插件)
                ->addColumn('content', 'text', ['comment' => '内容'])

                // 9. 以特殊字符结尾的规则：_id, user_id,  int/varchar, 识别为关联字段，自动生成可自动完成的文本框，单选
                ->addColumn('user_id', 'integer', ['default' => 0, 'comment' => '会员ID'])

                // 10. 以特殊字符结尾的规则：_ids, user_ids,  varchar,  识别为关联字段，自动生成可自动完成的文本框，多选
                ->addColumn('admin_ids', 'string', ['default' => 0, 'comment' => '会员ID'])

                // 11. 以特殊字符结尾的规则：list, time_list, enum, 识别为列表字段，自动生成单选下拉列表
                ->addColumn('time1_list', 'enum', ['values' => ['normal', 'hidden'], 'default' => 'normal', 'comment' => '状态'])

                // 12. 以特殊字符结尾的规则: list, time_list, set, 识别为列表字段，自动生成多选下拉列表
                ->addColumn('time2_list', 'set', ['values' => ['normal', 'hidden'], 'default' => 'normal', 'comment' => '状态'])

                // 13.  以特殊字符结尾的规则: data, hobby_data, enum, 识别为选项字段，自动生成单选框
                ->addColumn('hobby_data', 'enum', ['values' => ['音乐', '读书'], 'null' => true, 'comment' => '爱好'])

                // 14. 以特殊字符结尾的规则: data, hobby_data, set, 识别为选项字段，自动生成复选框
                ->addColumn('hobby_data', 'set', ['values' => ['音乐', '读书'], 'null' => true, 'comment' => '爱好'])

                // 15. 以特殊字符结尾的规则: config_json, varchar, 识别为键值组件，自动生成键值录入组件
                ->addColumn('config_json', 'string', ['limit' => 1500, 'default' => '', 'comment' => '二维数组:title=标题,intro=介绍,author=作者,age=年龄'])

                // 16. 以特殊字符结尾的规则: switch, sites_witch, tinyint, 识别为开关字段，自动生成开关组件，默认值1为开，0为关
                ->addColumn('sites_witch', 'integer', ['limit' => MysqlAdapter::INT_TINY, 'default' => 1, 'comment' => '站点切换'])

                // 17. 以特殊字符结尾的规则: range， date_range, varchar, 识别为时间区间组件，自动生成时间区间组件
                ->addColumn('date_range', 'string', ['limit' => 1500, 'default' => '', 'comment' => '日期区间'])

                // 18. 以特殊字符结尾的规则: tag， article_tag, varchar, 识别为 Tags Input，自动生成标签输入组件
                ->addColumn('article_tag', 'string', ['limit' => 255, 'default' => '', 'comment' => '标签'])

                // 19. 以特殊字符结尾的规则: tags， article_tags, varchar, 识别为 Tags Input，自动生成标签输入组件
                ->addColumn('article_tags', 'string', ['limit' => 255, 'default' => '', 'comment' => '标签'])

                ->create();
        }

    }

    private function devCrudLog(): void
    {
        if (!$this->hasTable('dev_crud_log')) {
            $table = $this->table('dev_crud_log', [
                'id' => false,
                'comment' => '开发-CRUD记录表',
                'row_format' => 'DYNAMIC',
                'primary_key' => 'id',
                'collation' => 'utf8mb4_unicode_ci',
            ]);
            $table
                // 相关ID
                ->addColumn('id', 'integer', ['comment' => 'ID', 'signed' => false, 'identity' => true, 'null' => false])
                // 基本信息
                ->addColumn('table_name', 'string', ['limit' => 200, 'default' => '', 'comment' => '数据表名', 'null' => false])
                ->addColumn('table', 'text', ['null' => true, 'default' => null, 'comment' => '数据表数据'])
                ->addColumn('fields', 'text', ['null' => true, 'default' => null, 'comment' => '字段数据'])
                // 状态信息
                ->addColumn('status', 'enum', ['values' => 'delete,success,error,start', 'default' => 'start', 'comment' => '状态:delete=已删除,success=成功,error=失败,start=生成中', 'null' => false])
                // 时间相关
                ->addColumn('create_time', 'biginteger', ['signed' => false, 'null' => true, 'default' => null, 'comment' => '创建时间'])
                ->create();
        }
    }


}
