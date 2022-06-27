<?php


namespace DeathSatan\Hyperf\Validate\Collector;


use Hyperf\Di\MetadataCollector;
use Hyperf\Utils\ApplicationContext;

class ModelValidateCollector extends MetadataCollector
{
    /**
     * @var array
     */
    protected static $container = [];

    /**
     * @var array
     */
    protected static $result = [];

    public static function collectClass(string $class, string $annotation, $value): void
    {
        static::$container[$class][] = $value;
    }

    public static function result() {
        if (count(static::$result) == 0) {
            static::parseValidate();
        }
        return static::$result;
    }

    public static function parseValidate()
    {
        foreach(static::list() as $class => $modelValidate) {
            $result = [];
            foreach($modelValidate as $validate) {
                $result[] = [
                    'validate' => static::buildValidate($validate->validate),
                    'scene' => $validate->scene,
                    'event' => explode(',',$validate->event)
                ];
            }
            static::$result[$class] = $result;
        }
    }

    public static function buildValidate(string $validate)
    {
        return ApplicationContext::getContainer()->make($validate);
    }
}