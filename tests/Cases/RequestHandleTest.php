<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf Extend.
 *
 * @link     https://www.cnblogs.com/death-satan
 * @license  https://github.com/Death-Satan/hyperf-validate
 */
namespace HyperfTest\Cases;

use DeathSatan\Hyperf\Validate\Driver\RequestHandle;
use DeathSatan\Hyperf\Validate\Lib\AbstractValidate;

/**
 * @internal
 * @coversNothing
 */
class RequestHandleTest extends AbstractTestCase
{
    public function testProvide()
    {
        $requestHandle = $this->getMockRequestHandle();

        $params = [new \stdClass(), \Mockery::mock(AbstractValidate::class), 'test'];
        $requestHandle->allows()
            ->provide($params[0], $params[1], $params[2])
            ->andReturn([]);

        $result = $requestHandle->provide($params[0], $params[1], $params[2]);

        $this->assertIsArray($result, 'this is array');
    }

    protected function getMockRequestHandle()
    {
        return \Mockery::mock(RequestHandle::class);
    }
}
