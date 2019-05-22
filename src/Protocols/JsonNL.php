<?php
/**
 * workman encoder
 */
namespace Tourscool\RpcClient\Protocols;

/**
 * RPC 协议解析 相关
 * 协议格式为 [json字符串\n]
 * */
class JsonNL
{

    public function input($buffer)
    {
        // 获得换行字符"\n"位置
        $pos = strpos($buffer, "\n");
        // 没有换行符，无法得知包长，返回0继续等待数据
        if ($pos === false) {
            return 0;
        }
        // 有换行符，返回当前包长（包含换行符）
        return $pos+1;
    }

    public function encode($buffer)
    {

        return json_encode($buffer)."\n";
    }

    public  function decode($buffer)
    {
        return json_decode(trim($buffer), true);
    }
}
