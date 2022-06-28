<?php

namespace DeathSatan\Hyperf\Validate\Annotation;

use Hyperf\Di\Annotation\AbstractAnnotation;
use Hyperf\Di\Exception\NotFoundException;
use Hyperf\Utils\ApplicationContext;

/**
 * @Annotation
 * @Target({"CLASS", "METHOD"})
 */
class Validate extends AbstractAnnotation
{
    /**
     * @var string 验证器类名
     */
    public $validate;

    /**
     * @var string 场景名
     */
    public $scene = null;

    /**
     * 自定义提供验证数据方法
     * 为当前类的公共静态方法名称 如果设置了该项
     * 则会以 $controller->{$on_handle}()的数据为验证数据
     * @var string
     */
    public $on_handle = null;

    /**
     * @throws NotFoundException
     */
    public function __construct(...$value)
    {
        $formattedValue = $this->formatParams($value);
        foreach ($formattedValue as $key => $val) {
            if ($key === 'validate')
            {
                $this->checkValidate($val);
            }
            if (property_exists($this, $key)) {
                $this->{$key} = $val;
            }
        }
    }

    /**
     * 检查validate
     * @throws NotFoundException
     */
    protected function checkValidate($val)
    {
        if (!class_exists($val))
        {
            throw new NotFoundException('this is Validate Class['.$val.'] Not found');
        }
    }
}