<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf Extend.
 *
 * @link     https://www.cnblogs.com/death-satan
 * @license  https://github.com/Death-Satan/hyperf-validate
 */
namespace DeathSatan\Hyperf\Validate\Listeners;

use DeathSatan\Hyperf\Validate\Collector\ModelValidateCollector;
use DeathSatan\Hyperf\Validate\Exceptions\ModelValidateException;
use DeathSatan\Hyperf\Validate\Lib\AbstractValidate;
use Hyperf\Database\Model\Events\Creating;
use Hyperf\Database\Model\Events\Event;
use Hyperf\Database\Model\Events\Saving;
use Hyperf\Database\Model\Events\Updating;
use Hyperf\Database\Model\Model;
use Hyperf\Event\Contract\ListenerInterface;

class ModelValidateLister implements ListenerInterface
{
    public function listen(): array
    {
        return [
            Creating::class,
            Saving::class,
            Updating::class,
        ];
    }

    public function process(object $event)
    {
        if ($event instanceof Event) {
            $model_name = get_class($event->getModel());
            $collector_list = ModelValidateCollector::result()[$model_name] ?? [];
            foreach ($collector_list as $validate) {
                $this->check($validate, $event->getModel(), $event->getMethod());
            }
        }
    }

    protected function check(array $validate, Model $model, string $method)
    {
        /**
         * 验证器对象
         * @var AbstractValidate $validate_object
         */
        $validate_object = $validate['validate'];
        // 场景
        $scene = $validate['scene'];
        // 要监听的事件
        $events = $validate['event'];
        // 如果不是要监听的事件
        if (! in_array($method, $events)) {
            return true;
        }
        $data = $this->parse_data($model, $method);
        var_dump($data, $scene, $method);
        if ($scene !== null) {
            $validate_object = $validate_object->scene($scene);
        }
        if (($result = $validate_object->make($data, false)) !== true) {
            throw new ModelValidateException($result);
        }
    }

    /**
     * 获取要校检的数据.
     * @param $event
     */
    protected function parse_data(Model $model, $event): array
    {
        if ($event === 'creating') {
            return $model->getAttributes();
        }

        if ($event === 'saving' || $event === 'updating') {
            $dirty = $model->getDirty();
            return empty($dirty) ? $model->getAttributes() : $dirty;
        }
        return [];
    }
}
