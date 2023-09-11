<?php
/*
 * @author: 布尔
 * @name: 打印
 * @desc: 介绍
 * @LastEditTime: 2023-09-11 20:17:18
 * @FilePath: \yilianyun\src\Print.php
 */

namespace Eykj\Yilianyun;

use Eykj\Base\GuzzleHttp;
use Eykj\Yilinayun\Service;

class Print
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
     * @name: 文本打印
     * @param {*} $param
     * @return {*}
     */    
    public function index($param)
    {
        $timestamp = time();
        $data = eyc_array_key($param, 'machine_code,content,origin_id');
        $data['id'] = $this->Service->get_uuid4($param);
        $data['sign'] = md5(env('YILIANYUN_CLIENT_ID') . $timestamp . env('YILIANYUN_CLIENT_SECRET'));
        $data['timestamp'] = $timestamp;
        $data['client_id'] = env('YILIANYUN_CLIENT_ID');
        $data['access_token'] =$this->Service->get_access_token($param);
        return $this->GuzzleHttp->post($this->url."/print/index", $data);
    }
}
