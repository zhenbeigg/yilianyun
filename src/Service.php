<?php
/*
 * @author: 布尔
 * @name: 服务类
 * @desc: 介绍
 * @LastEditTime: 2023-09-11 20:36:11
 * @FilePath: \yilianyun\src\Service.php
 */

namespace Eykj\Yilianyun;

use Eykj\Base\GuzzleHttp;

class Service
{
    protected ?GuzzleHttp $GuzzleHttp;

    // 通过设置参数为 nullable，表明该参数为一个可选参数
    public function __construct(?GuzzleHttp $GuzzleHttp)
    {
        $this->GuzzleHttp = $GuzzleHttp;
    }
    /**
     * 请求地址
     */
    protected $url = 'https://open-api.10ss.net';

    /**
     * @author: 布尔
     * @name: 获取token
     * @param {*} $param
     * @return {*}
     */
    public function get_access_token($param)
    {
        if (!redis()->get($param->client_id . "_yilianyun_access_token")) {
            $timestamp = time();
            $data = array("grant_type" => "client_credentials", "scope" => "all", "timestamp" => $timestamp, "client_id" => env('YILIANYUN_CLIENT_ID'), "sign" => md5(env('YILIANYUN_CLIENT_ID') . $timestamp . env('YILIANYUN_CLIENT_SECRET')), "id" => $this->get_uuid4($param));
            $rs = $this->GuzzleHttp->post($this->url . "/oauth/oauth", $data);
            if ($rs["error"] == 0) {
                redis()->set($param->client_id . "_yilianyun_access_token", $rs['body']["access_token"], $rs['body']["expires_in"]);
                $access_token = $rs['body']["access_token"];
            } else {
                error($rs["error"], $rs["error_description"]);
            }
        } else {
            $access_token = redis()->get($param->client_id . "_yilianyun_access_token");
        }
        return $access_token;
    }

    /**
     * @author: 布尔
     * @name: 获取uuid
     * @param {*} $param
     * @return {*}
     */
    public function get_uuid4($param)
    {
        mt_srand((float)microtime() * 10000);
        $charid = strtolower(md5(uniqid(rand(), true)));
        $hyphen = '-';
        $uuidV4 =
            substr($charid, 0, 8) . $hyphen .
            substr($charid, 8, 4) . $hyphen .
            substr($charid, 12, 4) . $hyphen .
            substr($charid, 16, 4) . $hyphen .
            substr($charid, 20, 12);
        return $uuidV4;
    }
}
