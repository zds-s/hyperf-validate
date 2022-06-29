<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf Extend.
 *
 * @link     https://www.cnblogs.com/death-satan
 * @license  https://github.com/Death-Satan/hyperf-validate
 */
namespace DeathSatan\Hyperf\Validate\Annotation;

use DeathSatan\Hyperf\Validate\Collector\ModelValidateCollector;
use Hyperf\Di\Annotation\AbstractAnnotation;

/**
 * @Annotation
 * @Target({"CLASS"})
 */
class ModelValidate extends AbstractAnnotation
{
    /**
     * 验证器类名.
     * @var string
     */
    public $validate;

    /**
     * 验证器场景.
     * @var ?string
     */
    public $scene;

    /**
     * 要在什么事件内进行数据校验.
     * @var string[]
     */
    public $event = 'creating,updating,saving';

    public function __construct($value)
    {
        parent::__construct($value);
    }

    public function collectClass(string $className): void
    {
        ModelValidateCollector::collectClass($className, static::class, $this);
    }
}
