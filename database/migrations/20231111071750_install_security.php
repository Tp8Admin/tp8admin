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
class InstallSecurity extends Migrator
{
    public function change(): void
    {
        // 安全相关
        $this->securityDataRecycle(); // 安全-回收规则表
        $this->securityDataRecycleLog(); // 安全-数据回收记录
        $this->securitySensitiveData(); // 安全-敏感数据规则
        $this->securitySensitiveDataLog(); // '安全-敏感数据修改记录
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
}
