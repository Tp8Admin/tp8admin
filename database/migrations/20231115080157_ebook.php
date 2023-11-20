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

use think\migration\Migrator;

class Ebook extends Migrator
{
    public function change(): void
    {
        $this->ebook();  // 开发-测试-最简表1
    }

    private function ebook(): void
    {

        if (!$this->hasTable('ebook')) {
            $table = $this->table('ebook', [
                'id' => false,
                'comment' => '电子书',
                'row_format' => 'DYNAMIC',
                'primary_key' => 'id',
                'collation' => 'utf8mb4_unicode_ci',
            ]);

            $table
                // 相关ID
                ->addColumn('id', 'integer', ['comment' => 'ID', 'signed' => false, 'identity' => true, 'null' => false])
                ->addColumn('category1_id', 'integer', ['comment' => '分类1'])
                ->addColumn('category2_id', 'integer', ['comment' => '分类2'])
                // 基本信息
                ->addColumn('name', 'string', ['default' => null, 'limit' => 50, 'comment' => '名称'])
                ->addColumn('description', 'string', ['default' => null, 'limit' => 200, 'comment' => '描述'])
                ->addColumn('cover', 'string', ['default' => null, 'limit' => 200, 'comment' => '封面'])
                ->addColumn('doc_count', 'integer', ['comment' => '文档数'])
                ->addColumn('vote_count', 'integer', ['comment' => '点赞数'])
                ->create();
        }

    }
}
