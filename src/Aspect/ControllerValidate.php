<?php

namespace DeathSatan\Hyperf\Validate\Aspect;

use DeathSatan\Hyperf\Validate\Exceptions\ValidateException;
use DeathSatan\Hyperf\Validate\Lib\AbstractValidate;
use Hyperf\Di\Container;
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
        \DeathSatan\Hyperf\Validate\Annotation\Validate::class
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
            $this->check($class,$current_object);
        }
        foreach ($annotation_data->method as $method)
        {
            $this->check($method,$current_object);
        }
    }

    /**
     * 验证处理
     * @param  $validate
     * @param object $current
     * @return void
     * @throws ValidateException
     */
    protected function check($validate,object $current):void
    {
        $on_handle = $validate->on_handle;
        $scene = $validate->scene;
        $validate = $this->makeValidate($validate->validate);
        $data = $this->handleData($on_handle,$current);
        if ($scene!==null)
        {
            $validate = $validate->scene($scene);
        }
        $validate->make($data,true);
    }

    //取要验证的数据
    protected function handleData($on_handle, $current)
    {
        if ($on_handle===null)
        {
            return $this->request->all();
        }
        if (!method_exists($current,$current))
        {
            throw new \RuntimeException('No '.$on_handle.' method found in this class');
        }
        return call_user_func_array([
            $current,$on_handle
        ],[]);
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