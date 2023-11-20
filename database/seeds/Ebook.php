<?php

use think\migration\Seeder;

class Ebook extends Seeder
{
    public function run(): void
    {
        $this->truncateData();
        $this->createEbookData(); // 电子书
    }

    protected function truncateData(): void
    {
        $sqlList = [
            'truncate table ebook',
        ];

        foreach ($sqlList as $sql) {
            \think\facade\Db::execute($sql);
        }
    }

    protected function createEbookData(): void
    {
        $data = [
            [
                'id' => 1,
                'name' => 'Spring Boot入门教程',
                'description' => '零基础入门 JAVA 开发， 企业级应用开发最佳首选框架',
            ],
            [
                'id' => 2,
                'name' => 'Vue 入门教程',
                'description' => '零基础入门 VUE 开发， 企业级应用开发最佳首选框架',
            ],
            [
                'id' => 3,
                'name' => 'Python 入门教程',
                'description' => '零基础入门 Python 开发， 企业级应用开发最佳首选框架',
            ],
            [
                'id' => 4,
                'name' => 'Mysql 入门教程',
                'description' => '零基础入门 Mysql 开发， 企业级应用开发最佳首选框架',
            ],
            [
                'id' => 5,
                'name' => 'Oracle 入门教程',
                'description' => '零基础入门 Oracle 开发， 企业级应用开发最佳首选框架',
            ],
        ];

        $table = $this->table('ebook');
        $table->insert($data)->save();
    }
}