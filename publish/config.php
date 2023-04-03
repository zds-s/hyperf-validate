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
return [
    /*
     * --------------------------------------------------------------------------------
     * 自定义数据提供者.
     * 如果你想自己向验证器提供数据，那么可以尝试修改这个选项
     * 指定类名,如果不存在则会抛出\RuntimeException异常
     * 请让你的自定义数据提供者实现\DeathSatan\Hyperf\Validate\Contract\CustomHandle契约.
     * 只有这样它才不会抛出异常.
     * --------------------------------------------------------------------------------
     */
    'customHandle' => \DeathSatan\Hyperf\Validate\Driver\RequestHandle::class,
];
