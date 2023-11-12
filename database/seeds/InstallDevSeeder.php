<?php

use think\migration\Seeder;

class InstallDevSeeder extends Seeder
{
    /**
     * Run Method.
     *
     * Write your database seeder using this method.
     *
     * More information on writing seeders is available here:
     * http://docs.phinx.org/en/latest/seeding.html
     */
    public function run(): void
    {

        $this->truncateData();
        $this->createDemo1Data();
        $this->createDemo2Data();
    }

    protected function truncateData(): void
    {
        $sqlList = [
            'truncate table dev_demo1',
        ];

        foreach ($sqlList as $sql) {
            \think\facade\Db::execute($sql);
        }
    }

    protected function createDemo1Data(): void
    {
        $data = [
            [
                'id'              => 1,
                'name'            => 'admin',
                'password'        => '792fe3eb2a0ce2f415961f0426cc2478',
            ],
        ];

        $table = $this->table('dev_demo1');
        $table->insert($data)->save();
    }

    protected function createDemo2Data(): void
    {
        $data = [
            [
                'id'              => 1,
                'name'            => 'admin',
            ],
        ];

        $table = $this->table('dev_demo2');
        $table->insert($data)->save();
    }
}
