<?php

namespace HyperfTest\Cases;

use DeathSatan\Hyperf\Validate\Lib\AbstractValidate;
use Hyperf\Contract\ValidatorInterface;

class AbstractValidateTest extends AbstractTestCase
{
    protected function getValidate()
    {
       return \Mockery::mock(AbstractValidate::class);
    }

    public function testAttributes()
    {
        $this->assertClassHasAttribute('scenes',AbstractValidate::class);
        $this->assertClassHasAttribute('scene',AbstractValidate::class);
        $this->assertClassHasAttribute('validateFactory',AbstractValidate::class);
        $this->assertClassHasAttribute('eventDispatcher',AbstractValidate::class);
    }


    public function testFunction()
    {
        $validate = $this->getValidate();

        $testValue = 'demo';
        $validate->allows()
            ->scene($testValue)->andReturnSelf();

        $this->assertEquals($validate->scene($testValue),$validate);

        $validateValue = [
            'test'=>'test'
        ];
        $validate->allows()
            ->make($validateValue,false)
            ->andReturn(\Mockery::mock(ValidatorInterface::class));

        $this->assertTrue( $validate->make($validateValue,false) instanceof ValidatorInterface);


        $this->expectException(\DeathSatan\Hyperf\Validate\Exceptions\ValidateException::class);
        $exceptionValue = [
            'test'=>'demo'
        ];
        $validate->allows()
            ->make($exceptionValue,true)
            ->andThrow(
                \Mockery::mock(\DeathSatan\Hyperf\Validate\Exceptions\ValidateException::class)
            );
        $validate->make($exceptionValue,true);

    }

    protected function tearDown(): void
    {
        \Mockery::close();
    }
}