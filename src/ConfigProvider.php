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
use DeathSatan\Hyperf\Validate\Commands\ValidateCommand;

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

            ],
            'aspects' =>[
                ControllerValidate::class
            ],
            'annotations' => [
                'scan' => [
                    'paths' => [
                        __DIR__,
                    ]
                ],
            ],
            'publish'=>[
                [
                    'id'=>'death-satan/hyperf-validate',
                    'description'=>'validate of config file',
                    'source'=>__DIR__.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'publish'.DIRECTORY_SEPARATOR.'config.php',
                    'destination'=>BASE_PATH.DIRECTORY_SEPARATOR.'config'.DIRECTORY_SEPARATOR.'autoload'.DIRECTORY_SEPARATOR.'validate.php'
                ]
            ]
        ];
    }
}
