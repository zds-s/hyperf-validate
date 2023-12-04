<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf Extend.
 *
 * @link     https://www.cnblogs.com/death-satan
 * @license  https://github.com/Death-Satan/hyperf-validate
 */
namespace DeathSatan\Hyperf\Validate\Events;

class BeforeDataValidate
{
    public string $class;

    public array $data;

    public array $messages;

    public array $attributes;

    public array $rules;

    public ?string $scene;

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
