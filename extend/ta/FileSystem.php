<?php


namespace ta;

/**
 * 访问和操作文件系统
 */
class FileSystem
{

    /**
     * 检查目录/文件是否可写
     * @param $path
     * @return bool
     */
    public static function pathIsWritable($path): bool
    {
        if (DIRECTORY_SEPARATOR == '/' && !@ini_get('safe_mode')) {
            return is_writable($path);
        }

        if (is_dir($path)) {
            $path = rtrim($path, '/') . '/' . md5(mt_rand(1, 100) . mt_rand(1, 100));
            if (($fp = @fopen($path, 'ab')) === false) {
                return false;
            }

            fclose($fp);
            @chmod($path, 0777);
            @unlink($path);

            return true;
        } elseif (!is_file($path) || ($fp = @fopen($path, 'ab')) === false) {
            return false;
        }

        fclose($fp);
        return true;
    }
}

// 测试文件夹
//$file = new FileSystem();
//$result = $file::pathIsWritable('/Users/mac/Documents/wwwroot/code-tp8admin/tp8admin/config');
//echo $result;
//echo "\n";
//
//// 测试文件
//$result2 = $file::pathIsWritable('/Users/mac/Documents/wwwroot/code-tp8admin/tp8admin/config/database.php');
//echo $result2;
