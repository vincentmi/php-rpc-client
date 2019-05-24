<?php

namespace Test;

use PHPUnit\Framework\TestCase;
use Tourscool\RpcClient\Client;


class MainTest extends TestCase
{

    public function setUp()
    {
        parent::setUp();
    }

    public function testSet()
    {
        //$this->assertEquals(1,1);
        $client = new Client([ 'endpoints'=>'tcp://192.168.33.10:2010']);
        // print_r($client->service('greeting')->sayHello("Vincent"));
        //print_r($client->service('greeting')->sayHello("Pcc"));

        //print_r($client->greeting->sayHello("PCC1") . "+++++");
        $this->assertStringEndsWith('pcc1',$client->service('greeting')->sayHello("pcc1"));

        $this->assertStringEndsWith('pcc1',$client->greeting->sayHello("pcc1"));
    }

}