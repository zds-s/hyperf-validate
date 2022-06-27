<?php


namespace DeathSatan\Hyperf\Validate\Exceptions;


use Hyperf\Contract\ValidatorInterface;
use Throwable;

class ValidateException extends \Exception
{
    protected $validate;

    public function __construct(ValidatorInterface $validator)
    {
        parent::__construct($validator->errors()->first());
        $this->validate = $validator;
    }

    /**
     * @return ValidatorInterface
     */
    public function getValidate(): ValidatorInterface
    {
        return $this->validate;
    }
}