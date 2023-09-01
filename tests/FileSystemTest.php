<?php

use PHPUnit\Framework\TestCase;
use ta\FileSystem;

class FileSystemTest extends TestCase
{
    public function testPathIsWritable()
    {
        // 测试文件夹
        $writableFolder = FileSystem::pathIsWritable('/Applications/EasySrv/www/tp8admin/config');
        $this->assertTrue($writableFolder);

        // 测试文件
        $writableFile = FileSystem::pathIsWritable('/Applications/EasySrv/www/tp8admin/config/database.php');
        $this->assertTrue($writableFile);
    }

}
