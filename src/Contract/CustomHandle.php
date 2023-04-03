<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf Extend.
 *
 * @link     https://www.cnblogs.com/death-satan
 * @license  https://github.com/Death-Satan/hyperf-validate
 */
namespace DeathSatan\Hyperf\Validate\Contract;

use DeathSatan\Hyperf\Validate\Lib\AbstractValidate;

/**
 * 自定义提供validate契约.
 */
interface CustomHandle
{
    /**
     * 自定义验证数据.
     * @param object $current 当前使用Validate注解验证的对象。它会是任何对象。也许你可以用到 ($current instanceof 类名) 来验证它是否需要你来进行处理
     * @param AbstractValidate $validate 当前Validate注解所使用的 验证器对象
     * @param null|string $scene 当前Validate注解所使用的场景，默认情况下它是null
     */
    public function provide(object $current, AbstractValidate $validate, string $scene = null): array;
}
