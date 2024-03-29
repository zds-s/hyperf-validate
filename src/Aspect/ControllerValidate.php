<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf Extend.
 *
 * @link     https://www.cnblogs.com/death-satan
 * @license  https://github.com/Death-Satan/hyperf-validate
 */
namespace DeathSatan\Hyperf\Validate\Aspect;

use DeathSatan\Hyperf\Validate\Annotation\Validate;
use DeathSatan\Hyperf\Validate\Contract\CustomHandle;
use DeathSatan\Hyperf\Validate\Exceptions\ValidateException;
use DeathSatan\Hyperf\Validate\Lib\AbstractValidate;
use Hyperf\Collection\Arr;
use Hyperf\Di\Aop\AbstractAspect;
use Hyperf\Di\Aop\ProceedingJoinPoint;
use Hyperf\Di\Container;
use Hyperf\HttpServer\Contract\RequestInterface;
use Hyperf\HttpServer\Contract\ResponseInterface;
use Hyperf\Context\ApplicationContext;
use Psr\Container\ContainerInterface;
use function Hyperf\Config\config;
use function Hyperf\Support\make;

class ControllerValidate extends AbstractAspect
{
    public array $annotations = [
        Validate::class,
    ];

    protected Container|ContainerInterface $container;

    protected RequestInterface $request;

    protected ResponseInterface $response;

    public function __construct(
        ContainerInterface $container,
        RequestInterface $request,
        ResponseInterface $response
    ) {
        $this->container = $container;
        $this->request = $request;
        $this->response = $response;
    }

    /**
     * @throws ValidateException
     */
    public function process(ProceedingJoinPoint $proceedingJoinPoint)
    {
        $annotation_data = $proceedingJoinPoint->getAnnotationMetadata();
        $current_object = $proceedingJoinPoint->getInstance();
        foreach ($annotation_data->class as $class) {
            if ($class instanceof Validate) {
                $this->check($class, $current_object);
            }
        }
        foreach ($annotation_data->method as $method) {
            if ($method instanceof Validate) {
                $this->check($method, $current_object);
            }
        }

        return $proceedingJoinPoint->process();
    }

    /**
     * 验证处理.
     * @throws ValidateException
     */
    protected function check(Validate $validate, object $current): void
    {
        $scene = $validate->scene;
        $validate = $this->makeValidate($validate->validate);
        $data = $this->handleData($validate, $current, $scene);
        if ($scene !== null) {
            $validate = $validate->scene($scene);
        }
        $validate->make($data);
    }

    /**
     * 让hyperf container来管理handle.
     */
    protected function parseHandle($handle): CustomHandle
    {
        return ApplicationContext::getContainer()->make($handle);
    }

    /**
     * 获取要验证的数据.
     */
    protected function handleData(AbstractValidate $validate, object $current, string|array|null $scene): array
    {
        return $this
            ->parseHandle(
                $this->config('customHandle', \DeathSatan\Hyperf\Validate\Driver\RequestHandle::class)
            )
            ->provide($current, $validate, $scene);
    }

    protected function config(string $key = null, $default = null)
    {
        $config = config('validate');
        return $key === null ? $config : Arr::get($config, $key, $default);
    }

    /**
     * 生成验证器.
     */
    protected function makeValidate(string $validate): AbstractValidate
    {
        return make($validate);
    }
}
