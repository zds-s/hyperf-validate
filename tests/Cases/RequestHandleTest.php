<?php

namespace HyperfTest\Cases;

use DeathSatan\Hyperf\Validate\Driver\RequestHandle;
use DeathSatan\Hyperf\Validate\Lib\AbstractValidate;

class RequestHandleTest extends AbstractTestCase
{
    protected function getMockRequestHandle()
    {
        return \Mockery::mock(RequestHandle::class);
    }

    public function testProvide()
    {
        $requestHandle = $this->getMockRequestHandle();

        $params = [new \StdClass,\Mockery::mock(AbstractValidate::class),'test'];
        $requestHandle->allows()
            ->provide($params[0],$params[1],$params[2])
            ->andReturn([]);

        $result = $requestHandle->provide($params[0],$params[1],$params[2]);

        $this->assertIsArray($result,'this is array');
    }
}