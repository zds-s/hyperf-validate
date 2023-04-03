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
namespace DeathSatan\Hyperf\Validate\Events;

class BeforeDataValidate
{
    public $class;

    public $data;

    public $messages;

    public $attributes;

    public $rules;

    public $scene;

    public function __construct(string $class, array &$data, array &$rules, array &$messages, array &$attributes, ?string &$scene)
    {
        $this->class = $class;
        $this->data = $data;
        $this->messages = $messages;
        $this->attributes = $attributes;
        $this->rules = $rules;
        $this->scene = $scene;
    }
}
