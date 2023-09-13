<?php
/*
 * @author: 布尔
 * @name: 接口类
 * @desc: 介绍
 * @LastEditTime: 2023-09-13 16:07:44
 * @FilePath: \yilianyun\src\Api.php
 */

namespace Eykj\Yilianyun;

use Eykj\Base\GuzzleHttp;
use Eykj\Yilianyun\Service;
use function Hyperf\Support\env;

class Api
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
    public function printer_addprinter($param)
    {
        $timestamp = time();
        $data = eyc_array_key($param, 'machine_code,msign,print_name');
        $data['id'] = $this->Service->get_uuid4($param);
        $data['sign'] = md5(env('YILIANYUN_CLIENT_ID') . $timestamp . env('YILIANYUN_CLIENT_SECRET'));
        $data['timestamp'] = $timestamp;
        $data['client_id'] = env('YILIANYUN_CLIENT_ID');
        $data['access_token'] = $this->Service->get_access_token($param);
        return $this->GuzzleHttp->post($this->url . "/printer/addprinter", $data);
    }


    /**
     * @author: 布尔
     * @name: 文本打印
     * @param {*} $param
     * @return {*}
     */
    public function print_index($param)
    {
        $timestamp = time();
        $data = eyc_array_key($param, 'machine_code,content,origin_id');
        $data['id'] = $this->Service->get_uuid4($param);
        $data['sign'] = md5(env('YILIANYUN_CLIENT_ID') . $timestamp . env('YILIANYUN_CLIENT_SECRET'));
        $data['timestamp'] = $timestamp;
        $data['client_id'] = env('YILIANYUN_CLIENT_ID');
        $data['access_token'] = $this->Service->get_access_token($param);
        return $this->GuzzleHttp->post($this->url . "/print/index", $data);
    }
}
