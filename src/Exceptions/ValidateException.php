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
namespace DeathSatan\Hyperf\Validate\Exceptions;

use Hyperf\Contract\ValidatorInterface;

class ValidateException extends \Exception
{
    protected $validate;

    public function __construct(ValidatorInterface $validator)
    {
        parent::__construct($validator->errors()->first());
        $this->validate = $validator;
    }

    public function getValidate(): ValidatorInterface
    {
        return $this->validate;
    }
}
