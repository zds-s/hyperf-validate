<?php


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