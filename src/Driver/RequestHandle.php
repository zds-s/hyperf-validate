<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf Extend.
 *
 * @link     https://www.cnblogs.com/death-satan
 * @license  https://github.com/Death-Satan/hyperf-validate
 */
namespace DeathSatan\Hyperf\Validate\Driver;

use DeathSatan\Hyperf\Validate\Contract\CustomHandle;
use DeathSatan\Hyperf\Validate\Lib\AbstractValidate;
use Hyperf\HttpServer\Contract\RequestInterface;
use Hyperf\HttpServer\Contract\ResponseInterface;

/**
 * 自定义默认数据获取器.
 */
class RequestHandle implements CustomHandle
{
    protected $request;

    protected $response;

    public function __construct(
        RequestInterface $request,
        ResponseInterface $response
    ) {
        $this->request = $request;
        $this->response = $response;
    }

    /**
     * {@inheritDoc}
     */
    public function provide(object $current, AbstractValidate $validate, array|null|string $scene = null): array
    {
        return $this->request->all();
    }
}
