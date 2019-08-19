<?php
/**基于redis的简单同步队列扩展
 * 需要已经装载redis和php-redis扩展
 * @author Morty zhu
 */

namespace EasyList;


class EasyList
{

    private $redis;

    private static $instance = null;

    public $sync = true;
    public $ttr = 60;

    private function __construct($host,$pwd='',$port=22)
    {
        try{

            $this->redis = new \Redis();
            //连接
            $this->redis->connect($host, $port);
            //链接密码,默认为零
            $this->redis->auth($pwd);

        }catch (\Exception $e)
        {
            throw new \Exception('redis has not work,check redis status');
        }


    }
    //克隆方法私有化:禁止从外部克隆对象
    private function __clone(){}

    public static function getInstance($host,$pwd='',$port=22)
    {
        //检测当前类属性$instance是否已经保存了当前类的实例
        if (self::$instance == null) {

            self::$instance = new self($host,$pwd,$port);
        }

        return self::$instance;
    }

    // 消息标准消息结构
    public function make_list_message($tack_id,$delayTime,$body)
    {
        $return_array = [
            'tack_id'=>$tack_id,
            'delayTime'=>$delayTime,
            'ttr'=>$this->ttr,
            'body'=>$body
        ];

        return json_encode($return_array);
    }



}