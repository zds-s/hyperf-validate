<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf Extend.
 *
 * @link     https://www.cnblogs.com/death-satan
 * @license  https://github.com/Death-Satan/hyperf-validate
 */
namespace HyperfTest\Cases;

use DeathSatan\Hyperf\Validate\Lib\AbstractValidate;
use Hyperf\Contract\ValidatorInterface;

/**
 * @internal
 * @coversNothing
 */
class AbstractValidateTest extends AbstractTestCase
{
    protected function tearDown(): void
    {
        \Mockery::close();
    }

    public function testAttributes()
    {
        $this->assertClassHasAttribute('scenes', AbstractValidate::class);
        $this->assertClassHasAttribute('scene', AbstractValidate::class);
        $this->assertClassHasAttribute('validateFactory', AbstractValidate::class);
        $this->assertClassHasAttribute('eventDispatcher', AbstractValidate::class);
    }

    public function testFunction()
    {
        $validate = $this->getValidate();

        $testValue = 'demo';
        $validate->allows()
            ->scene($testValue)->andReturnSelf();

        $this->assertEquals($validate->scene($testValue), $validate);

        $validateValue = [
            'test' => 'test',
        ];
        $validate->allows()
            ->make($validateValue, false)
            ->andReturn(\Mockery::mock(ValidatorInterface::class));

        $this->assertTrue($validate->make($validateValue, false) instanceof ValidatorInterface);

        $this->expectException(\DeathSatan\Hyperf\Validate\Exceptions\ValidateException::class);
        $exceptionValue = [
            'test' => 'demo',
        ];
        $validate->allows()
            ->make($exceptionValue, true)
            ->andThrow(
                \Mockery::mock(\DeathSatan\Hyperf\Validate\Exceptions\ValidateException::class)
            );
        $validate->make($exceptionValue, true);
    }

    protected function getValidate()
    {
        return \Mockery::mock(AbstractValidate::class);
    }
}
