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

class InstallCommon extends Migrator
{
    public function change(): void
    {
        // 通用相关
        $this->commonConfig(); // 通用-系统配置
        $this->commonArea();// 通用-地区
        $this->commonAttachment();// 通用-附件
        $this->commonCaptcha();// 通用-验证码
        $this->commonToken();// 通用-TOKEN
    }

    // +----------------------------------------------------------------------
    // | 通用相关
    // +----------------------------------------------------------------------

    public function commonConfig(): void
    {
        if (!$this->hasTable('common_config')) {
            $table = $this->table('common_config', [
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
            $table = $this->table('common_area', [
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
            $table = $this->table('common_attachment', [
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
            $table = $this->table('common_captcha', [
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

}
