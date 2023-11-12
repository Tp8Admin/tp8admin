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

class InstallDev extends Migrator
{
    public function change(): void
    {
        // 开发相关
        $this->devDemo1();  // 开发-测试-最简表1
        $this->devDemo2();  // 开发-测试-最简表2
        $this->devTest1(); // 开发-测试-根据字段类型
        $this->devTest2(); // 开发-测试-特殊字段
        $this->devTest3(); // 开发-测试-以特殊字符结尾的规则
        $this->devCrudLog(); // 开发-CRUD记录表
    }
    // +----------------------------------------------------------------------
    // | 开发相关
    // +----------------------------------------------------------------------


    private function devDemo1(): void
    {
        if (!$this->hasTable('dev_demo1')) {
            $table = $this->table('dev_demo1', [
                'id' => false,
                'comment' => '开发-测试-最简表1',
                'row_format' => 'DYNAMIC',
                'primary_key' => 'id',
                'collation' => 'utf8mb4_unicode_ci',
            ]);

            $table
                // 相关ID
                ->addColumn('id', 'integer', ['comment' => 'ID', 'signed' => false, 'identity' => true, 'null' => false])
                ->addColumn('name', 'string', ['default' => null, 'limit' => 50, 'comment' => '名称'])
                ->addColumn('password', 'string', ['default' => null, 'limit' => 50, 'comment' => '密码'])
                ->create();
        }

    }

    private function devDemo2(): void
    {
        if (!$this->hasTable('dev_demo2')) {
            $table = $this->table('dev_demo2', [
                'id' => false,
                'comment' => '开发-测试-最简表2',
                'row_format' => 'DYNAMIC',
                'primary_key' => 'id',
                'collation' => 'utf8mb4_unicode_ci',
            ]);

            $table
                // 相关ID
                ->addColumn('id', 'integer', ['comment' => 'ID', 'signed' => false, 'identity' => true, 'null' => false])
                ->addColumn('name', 'string', ['default' => null, 'limit' => 50, 'comment' => '名称'])
                ->create();
        }

    }

    private function devTest1(): void
    {
        if (!$this->hasTable('dev_test1')) {
            $table = $this->table('dev_test1', [
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
                ->addColumn('nick_name', 'string', ['default' => 0, 'limit' => 20, 'comment' => '会员昵称'])
                ->addColumn('true_name', 'string', ['default' => 0, 'limit' => 255, 'comment' => '会员真实姓名'])
                ->create();

        }

    }

    private function devTest2(): void
    {
        if (!$this->hasTable('dev_test2')) {
            $table = $this->table('dev_test2', [
                'comment' => '开发-测试-特殊字段',
                'row_format' => 'DYNAMIC',
                'primary_key' => 'id',
                'collation' => 'utf8mb4_unicode_ci',
            ]);

            $table
                // 1. 特殊字段: user_id, 会员ID, int, 将生成选择会员的SelectPage组件，单选
                ->addColumn('user_id', 'integer', ['default' => 0, 'comment' => '会员ID'])
                // 2. 特殊字段: user_ids, 会员ID集合, varchar, 将生成选择会员的SelectPage组件，多选
                ->addColumn('user_ids', 'string', ['default' => 0, 'comment' => '会员ID'])
                // 3. 特殊字段: admin_id, 管理员ID, int, 将生成选择管理员的SelectPage组件
                ->addColumn('admin_id', 'integer', ['default' => 0, 'comment' => '管理员ID'])
                // 4. 特殊字段: admin_ids, 管理员ID集合, varchar, 将生成选择管理员的SelectPage组件，多选
                ->addColumn('admin_ids', 'string', ['default' => 0, 'comment' => '会员ID'])
                // 5. 特殊字段： category_id， 分类ID， int， 将生成选择分类的下拉框，分类类型根据去掉前缀的表名，单选
                ->addColumn('category_id', 'integer', ['default' => 0, 'comment' => '分类ID(单选)'])
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
                ->addColumn('hobby_data2', 'set', ['values' => ['音乐', '读书'], 'null' => true, 'comment' => '爱好'])

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
