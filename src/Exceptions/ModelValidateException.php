<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf Extend.
 *
 * @link     https://www.cnblogs.com/death-satan
 * @license  https://github.com/Death-Satan/hyperf-validate
 */
namespace DeathSatan\Hyperf\Validate\Exceptions;

use Hyperf\Contract\ValidatorInterface;

class ModelValidateException extends \Exception
{
    protected $validate;

    public function __construct(ValidatorInterface $validator)
    {
        parent::__construct($validator->errors()->first());
        $this->validate = $validator;
    }
}
