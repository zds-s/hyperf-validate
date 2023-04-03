<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf Extend.
 *
 * @link     https://www.cnblogs.com/death-satan
 * @license  https://github.com/Death-Satan/hyperf-validate
 */
namespace DeathSatan\Hyperf\Validate\Annotation;

use Attribute;
use Hyperf\Di\Annotation\AbstractAnnotation;
use Hyperf\Di\Exception\NotFoundException;

#[\Attribute(\Attribute::TARGET_CLASS | \Attribute::TARGET_METHOD)]
class Validate extends AbstractAnnotation
{
    /**
     * Validate constructor.
     * @param string $validate 验证器类名
     * @param null|array|string $scene 验证场景
     */
    public function __construct(public string $validate, public array|string|null $scene = null)
    {
    }

    /**
     * 检查validate.
     * @param mixed $val
     * @throws NotFoundException
     */
    protected function checkValidate($val)
    {
        if (! class_exists($val)) {
            throw new NotFoundException('this is Validate Class[' . $val . '] Not found');
        }
    }
}
