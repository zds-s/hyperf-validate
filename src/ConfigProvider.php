<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://doc.hyperf.io
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE
 */
namespace DeathSatan\Hyperf\Validate;

use DeathSatan\Hyperf\Validate\Aspect\ControllerValidate;
use DeathSatan\Hyperf\Validate\Collector\ModelValidateCollector;
use DeathSatan\Hyperf\Validate\Commands\ValidateCommand;
use DeathSatan\Hyperf\Validate\Listeners\ModelValidateLister;

class ConfigProvider
{
    public function __invoke(): array
    {
        return [
            'dependencies' => [

            ],
            'commands' => [
                ValidateCommand::class
            ],
            'listeners'=>[
                ModelValidateLister::class
            ],
            'aspects' =>[
                ControllerValidate::class
            ],
            'annotations' => [
                'scan' => [
                    'paths' => [
                        __DIR__,
                    ],
                    'collectors'=>[
                        ModelValidateCollector::class
                    ]
                ],
            ],
        ];
    }
}
