<?php
/*
 * @author: 布尔
 * @name: 打印机
 * @desc: 介绍
 * @LastEditTime: 2023-09-11 17:40:53
 * @FilePath: \yilianyun\src\Printer.php
 */

namespace Eykj\Yilinayun;

use Eykj\Base\GuzzleHttp;
use Eykj\Yilinayun\Service;

class Printer
{
    protected ?GuzzleHttp $GuzzleHttp;

    protected ?Service $Service;

    // 通过设置参数为 nullable，表明该参数为一个可选参数
    public function __construct(?GuzzleHttp $GuzzleHttp, Service $Service)
    {
        $this->GuzzleHttp = $GuzzleHttp;
        $this->Service = $Service;
    }

    /**
     * 请求地址
     */
    protected $url = 'https://open-api.10ss.net';

    /**
     * @author: 布尔
     * @name: 绑定打印机
     * @param {*} $param
     * @return {*}
     */
    public function addprinter($param)
    {
        $access_token = $this->Service->get_access_token($param);
        $uuid = $this->Service->get_uuid4($param);
        $timestamp = time();
        $sign = md5(env('YILIANYUN_CLIENT_ID') . $timestamp . env('YILIANYUN_CLIENT_SECRET'));
        $data = array("client_id" => $param->client_id, "machine_code" => $param->machine_code, "msign" => $param->msign, "access_token" => $access_token, "sign" => $sign, "id" => $uuid, "timestamp" => $timestamp, "print_name" => $param->print_name);

        $timestamp = time();
        $data = eyc_array_key($param, 'machine_code,print_name');
        $data['id'] = $this->Service->get_uuid4($param);
        $data['sign'] = md5(env('YILIANYUN_CLIENT_ID') . $timestamp . env('YILIANYUN_CLIENT_SECRET'));
        $data['timestamp'] = $timestamp;
        $data['client_id'] = env('YILIANYUN_CLIENT_ID');
        $data['access_token'] = $this->Service->get_access_token($param);


        return $this->GuzzleHttp->post($this->url . "/printer/addprinter", $data);
    }
}
