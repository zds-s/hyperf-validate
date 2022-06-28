<?php

namespace DeathSatan\Hyperf\Validate\Aspect;

use DeathSatan\Hyperf\Validate\Annotation\Validate;
use DeathSatan\Hyperf\Validate\Contract\CustomHandle;
use DeathSatan\Hyperf\Validate\Driver\RequestHandle;
use DeathSatan\Hyperf\Validate\Exceptions\ValidateException;
use DeathSatan\Hyperf\Validate\Lib\AbstractValidate;
use Hyperf\Contract\ConfigInterface;
use Hyperf\Di\Container;
use Hyperf\Utils\ApplicationContext;
use Hyperf\Utils\Arr;
use Psr\Container\ContainerInterface;
use Hyperf\Di\Aop\AbstractAspect;
use Hyperf\Di\Aop\ProceedingJoinPoint;
use Hyperf\HttpServer\Contract\RequestInterface;
use Hyperf\HttpServer\Contract\ResponseInterface;

class ControllerValidate extends AbstractAspect
{
    /**
     * @var ContainerInterface|Container
     */
    protected $container;

    protected $request;

    protected $response;

    protected $config;
    public function __construct(
        ContainerInterface $container,
        RequestInterface $request,
        ResponseInterface $response
                                )
    {
        $this->container = $container;
        $this->request = $request;
        $this->response = $response;
    }

    public $annotations = [
       Validate::class
    ];

    /**
     * @throws ValidateException
     */
    public function process(ProceedingJoinPoint $proceedingJoinPoint)
    {
        $annotation_data = $proceedingJoinPoint->getAnnotationMetadata();
        $current_object = $proceedingJoinPoint->getInstance();
        foreach ($annotation_data->class as $class)
        {
            if ($class instanceof Validate) {
                $this->check($class, $current_object);
            }
        }
        foreach ($annotation_data->method as $method)
        {
            if ($method instanceof Validate) {
                $this->check($method, $current_object);
            }
        }

        return $proceedingJoinPoint->process();
    }

    /**
     * 验证处理
     * @param Validate $validate
     * @param object $current
     * @return void
     * @throws ValidateException
     */
    protected function check(Validate $validate, object $current):void
    {
        $scene = $validate->scene;
        $validate = $this->makeValidate($validate->validate);
        $data = $this->handleData($validate,$current,$scene);
        if ($scene!==null)
        {
            $validate = $validate->scene($scene);
        }
        $validate->make($data);
    }

    /**
     * 让hyperf container来管理handle
     * @param $handle
     * @return CustomHandle
     */
    protected function parseHandle($handle):CustomHandle
    {
        return ApplicationContext::getContainer()->make($handle);
    }

    /**
     * 获取要验证的数据
     * @param AbstractValidate $validate
     * @param object $current
     * @return array
     */
    protected function handleData(AbstractValidate $validate,object $current,?string $scene):array
    {
        $customHandle = $this->config('customHandle',\DeathSatan\Hyperf\Validate\Driver\RequestHandle::class);
        $handle = $this->parseHandle($customHandle);
        return $handle->provide($current,$validate,$scene);
    }

    protected function config(string $key = null,$default = null)
    {
        $config = config('validate');
        return $key===null?$config:Arr::get($config,$key,$default);
    }

    /**
     * 生成验证器
     * @param string $validate
     * @return AbstractValidate
     */
    protected function makeValidate(string $validate):AbstractValidate
    {
        return make($validate);
    }
}