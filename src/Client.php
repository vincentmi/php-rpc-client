<?php

namespace App\Utils;


class RpcClient {

    private  $timeout = 5;

    private $connection = null;

    private $address = "tcp://127.0.0.1:80";

    public function openConnection()
    {
        $this->connection = stream_socket_client($this->address, $err_no, $err_msg);
        if (!$this->connection) {
            throw new Exception("can not connect to $this->address , $err_no:$err_msg");
        }
        stream_set_blocking($this->connection, true);
        stream_set_timeout($this->connection, $this->timeout);
    }

    public function closeConnection()
    {
        fclose($this->connection);
        $this->connection = null;
    }

    public function sendData($method, $arguments)
    {
        $this->openConnection();
        $bin_data = JsonNL::encode(array(
            's' => $this->serviceName,
            'm' => $method,
            'p' => $arguments,
        ));
        if (fwrite($this->connection, $bin_data) !== strlen($bin_data)) {
            throw new RpcException('send data error');
        }
        return true;
    }


    public function call($method,$params)
    {

    }


    public function __call($name, $arguments)
    {

    }

}