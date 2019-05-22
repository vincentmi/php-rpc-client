<?php

namespace Tourscool\RpcClient;
use Tourscool\RpcClient\Exceptions\RpcException;

class Client
{

    protected $timeout = 5;

    protected $connection = null;

    protected $address = [];

    protected $serviceName = null;

    protected $encoder  = null;

    public function __construct($config)
    {
        if(isset($config['endpoints']))
        {
            $this->setEndpoints($config['endpoints']);
        }
    }

    public function setEndpoints($address)
    {
        if(!is_array($address))
        {
            $address = explode(',',strval($address));
        }
        $this->address = $address;
        return $this;
    }

    public function openConnection()
    {

        $address = $this->address[array_rand($this->address,1)];
        $connection = stream_socket_client($address, $err_no, $err_msg);
        if (!$connection) {
            throw new RpcException("can not connect to $this->address , $err_no:$err_msg", $err_no);
        }
        stream_set_blocking($connection, true);
        stream_set_timeout($connection, $this->timeout);
        $this->connection = $connection;
    }


    protected function getEncoder()
    {
        if($this->encoder == null)
        {
            $this->encoder = new \Tourscool\RpcClient\Protocols\JsonNL();
        }
        return $this->encoder;
    }

    public function closeConnection()
    {
        if ($this->connection != null) {
            fclose($this->connection);
        }
        $this->connection = null;
    }

    public function service($name)
    {
        $this->serviceName = $name;
        return $this;
    }

    public function getServiceName()
    {
        return $this->serviceName;
    }

    protected function perform($method, $arguments)
    {
        $this->openConnection();
        $bin_data = $this->getEncoder()->encode(array(
            's' => $this->getServiceName(),
            'm' => $method,
            'p' => $arguments,
            'k' => uniqid()
        ));
        if (fwrite($this->connection, $bin_data) !== strlen($bin_data)) {
            throw new RpcException('send data error',301);
        }

        $ret = fgets($this->connection);
        //$this->closeConnection();
        if (!$ret) {
            throw new RpcException("receive error",303);
        }
        $result =  $this->getEncoder()->decode($ret);
        if($result['code'] == 200 )
        {
            return $result['data'];
        }else{
            throw new RpcException($result['message'] , $result['code']);
        }
    }


    public function call($method, $params)
    {
        return $this->perform($method,$params);
    }


    public  function __call($name, $arguments)
    {
        return $this->perform($name,$arguments);
    }

}