<?php
// 应用公共文件

use \think\Response;
use \think\exception\HttpResponseException;

if (!function_exists('ip_check')) {

    /**
     * IP 检查
     * @param string|null $ip IP地址
     * @return void
     */
    function ip_check(string $ip = null): void
    {
        $ip = is_null($ip) ? request()->ip() : $ip;
        $noAccess = get_sys_config('no_access_ip');
        $noAccess = !$noAccess ? [] : array_filter(explode("\n", str_replace("\r\n", "\n", $noAccess)));
        if ($noAccess) {
            $response = Response::create(['msg' => 'No permission request'], 'json', 403);
            throw new  HttpResponseException($response);
        }
    }


}

if (!function_exists('get_sys_config')) {

    /**
     * 获取站点的系统配置, 不传递参数则获取所有配置项
     */
    function get_sys_config(string $name = ''): void
    {
        if ($name) {
            // 直接使用->value('value')不能使用到模型的类型格式化
//            $config = ConfigMode
        }
    }
}















