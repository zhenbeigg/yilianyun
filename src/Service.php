<?php
/*
 * @author: 布尔
 * @name: 服务类
 * @desc: 介绍
 * @LastEditTime: 2023-09-11 17:43:58
 * @FilePath: \yilianyun\src\Service.php
 */

namespace Eykj\Yilianyun;

use Eykj\Base\GuzzleHttp;

class Xzqh
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


    public function get_access_token($param)
    {
        if (!\PhalApi\DI()->cache->get($param->client_id . "yilianyun_access_token")) {
            $curl = new CURL;
            $timestamp = time();
            $uuid = self::get_uuid4($param);
            $sign = md5($param->client_id . $timestamp . $param->client_secret);
            $data = array("grant_type" => "client_credentials", "scope" => "all", "timestamp" => $timestamp, "id" => $uuid, "client_id" => $param->client_id, "sign" => $sign, "id" => $uuid, "timestamp" => $timestamp);
            $rs = $curl->post("https://open-api.10ss.net/oauth/oauth", $data);
            if ($rs["error"] == 0) {
                \PhalApi\DI()->cache->set($param->client_id . "yilianyun_access_token", $rs['body']["access_token"], $rs['body']["expires_in"]);
                $access_token = $rs['body']["access_token"];
            } else {
                \PhalApi\DI()->logger->error($param->client_id . "yilianyun_access_token", $rs);
                throw new BadRequestException($rs["error_description"], 1);
            }
        } else {
            $access_token = \PhalApi\DI()->cache->get($param->client_id . "yilianyun_access_token");
        }
        return $access_token;
    }
    /**
     * @Author: 布尔
     * @name: 获取uuid
     * @Date: 2021-02-23 16:07:33
     * @account: public
     * @param {*} $param
     * @return {*}
     */
    /**
     * @author: 布尔
     * @name: 方法名
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