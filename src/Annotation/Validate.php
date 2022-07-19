<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://hyperf.wiki
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE
 */
namespace DeathSatan\Hyperf\Validate\Annotation;

use Attribute;
use Hyperf\Di\Annotation\AbstractAnnotation;
use Hyperf\Di\Exception\NotFoundException;

#[Attribute(Attribute::TARGET_CLASS)]
class Validate extends AbstractAnnotation
{
    /**
     * Validate constructor.
     * @param string $validate 验证器类名
     * @param array|string $scene 验证场景
     */
    public function __construct(public string $validate, public array|string $scene = [])
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
