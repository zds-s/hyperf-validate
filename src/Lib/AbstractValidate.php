<?php


namespace DeathSatan\Hyperf\Validate\Lib;

use DeathSatan\Hyperf\Validate\Events\BeforeDataValidate;
use DeathSatan\Hyperf\Validate\Exceptions\ValidateException;
use Hyperf\Contract\ValidatorInterface;
use Hyperf\Utils\ApplicationContext;
use Hyperf\Utils\Arr;
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

    public function __construct()
    {
        $this->validateFactory = ApplicationContext::getContainer()->get(ValidatorFactoryInterface::class);
        $this->eventDispatcher = ApplicationContext::getContainer()->get(EventDispatcherInterface::class);
    }

    protected $scenes =[];

    /**
     * 自定义错误消息
     * @return array
     */
    protected function messages():array
    {
        return [];
    }

    /**
     * 自定义验证属性
     * @return array
     */
    protected function attributes():array
    {
        return [];
    }

    /**
     * 规则
     * @return array
     */
    protected function rules():array
    {
        return [];
    }

    /**
     * 验证数据
     * @param array $data
     * @param bool $is_throw
     * @return bool|ValidatorInterface
     * @throws ValidateException
     */
    public function make(array $data,$is_throw=true)
    {
        //场景处理
        [$rules,$messages,$attributes,$data] = $this->parseScene($data);
        $scene = $this->getScene();
        $this->eventDispatcher
            ->dispatch(new BeforeDataValidate(static::class,$data,$rules,$messages,$attributes,$scene));

        $validator = $this->validateFactory->make(
            $data,
            $rules,
            $messages,
            $attributes
        );
        if (!$validator->fails())
        {
            return true;
        }
        if ($is_throw) {
            throw new ValidateException($validator);
        }
        return $validator;
    }

    /**
     * @var null|string 当前场景
     */
    protected $scene = null;

    /**
     * 获取当前场景
     * @return string|null
     */
    protected function getScene():?string
    {
        return $this->scene;
    }

    /**
     * 设置场景
     * @param string $scene
     * @return $this
     */
    public function scene(string $scene):self
    {
        $this->scene = $scene;
        return $this;
    }

    /**
     * 获取要处理的值
     * @param array $data
     * @return array[]
     */
    protected function parseScene(array $data):array
    {
        if ($this->scene===null || empty($this->scenes[$this->scene]))
        {
            return [
                $this->rules(),
                $this->messages(),
                $this->attributes(),
                $data
            ];
        }
        $scene_map = $this->scenes[$this->scene];
        if (!is_array($scene_map))
        {
            throw new \RuntimeException('Validate['.static::class.'] scene type Error!');
        }
        $data = Arr::only($data,$scene_map);
        $rules = Arr::only($this->rules(),$scene_map);
        $messages = $this->messages();
        $attributes = $this->attributes();
        return [
            $rules,$messages,$attributes,$data
        ];
    }
}