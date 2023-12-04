<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf Extend.
 *
 * @link     https://www.cnblogs.com/death-satan
 * @license  https://github.com/Death-Satan/hyperf-validate
 */
namespace DeathSatan\Hyperf\Validate\Lib;

use DeathSatan\Hyperf\Validate\Events\BeforeDataValidate;
use DeathSatan\Hyperf\Validate\Exceptions\ValidateException;
use Hyperf\Collection\Arr;
use Hyperf\Contract\ValidatorInterface;
use Hyperf\Context\ApplicationContext;
use Hyperf\Validation\Contract\ValidatorFactoryInterface;
use Psr\EventDispatcher\EventDispatcherInterface;

class AbstractValidate
{
    /**
     * @var ValidatorFactoryInterface
     */
    protected $validateFactory;

    /**
     * @var EventDispatcherInterface
     */
    protected $eventDispatcher;

    protected $scenes = [];

    /**
     * @var null|string 当前场景
     */
    protected $scene;

    public function __construct()
    {
        $this->validateFactory = ApplicationContext::getContainer()->get(ValidatorFactoryInterface::class);
        $this->eventDispatcher = ApplicationContext::getContainer()->get(EventDispatcherInterface::class);
    }

    /**
     * 验证数据.
     * @param bool $is_throw
     * @return bool|ValidatorInterface
     * @throws ValidateException
     */
    public function make(array $data, $is_throw = true)
    {
        // 场景处理
        [$rules,$messages,$attributes,$data] = $this->parseScene($data);
        $scene = $this->getScene();
        $this->eventDispatcher
            ->dispatch(new BeforeDataValidate(static::class, $data, $rules, $messages, $attributes, $scene));

        $validator = $this->validateFactory->make(
            $data,
            $rules,
            $messages,
            $attributes
        );
        if (! $validator->fails()) {
            return true;
        }
        if ($is_throw) {
            throw new ValidateException($validator);
        }
        return $validator;
    }

    /**
     * 设置场景.
     * @return $this
     */
    public function scene(string $scene): self
    {
        $this->scene = $scene;
        return $this;
    }

    /**
     * 自定义错误消息.
     */
    protected function messages(): array
    {
        return [];
    }

    /**
     * 自定义验证属性.
     */
    protected function attributes(): array
    {
        return [];
    }

    /**
     * 规则.
     */
    protected function rules(): array
    {
        return [];
    }

    /**
     * 获取当前场景.
     */
    protected function getScene(): ?string
    {
        return $this->scene;
    }

    /**
     * 获取要处理的值
     * @return array[]
     */
    protected function parseScene(array $data): array
    {
        if ($this->scene === null || empty($this->scenes[$this->scene])) {
            return [
                $this->rules(),
                $this->messages(),
                $this->attributes(),
                $data,
            ];
        }
        $scene_map = $this->scenes[$this->scene];
        if (! is_array($scene_map)) {
            throw new \RuntimeException('Validate[' . static::class . '] scene type Error!');
        }
        $data = Arr::only($data, $scene_map);
        $rules = Arr::only($this->rules(), $scene_map);
        $messages = $this->messages();
        $attributes = $this->attributes();
        return [
            $rules, $messages, $attributes, $data,
        ];
    }
}
