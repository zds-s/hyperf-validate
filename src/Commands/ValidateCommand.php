<?php


namespace DeathSatan\Hyperf\Validate\Commands;


class ValidateCommand extends GeneratorCommand
{
    public function __construct()
    {
        parent::__construct('gen:validate');
        $this->setDescription('Create a new validate class');
    }

    protected function getStub(): string
    {
        return $this->getConfig()['stub'] ?? __DIR__ . '/stubs/validate.stub';
    }

    protected function getDefaultNamespace(): string
    {
        return $this->getConfig()['namespace'] ?? 'App\\Validate';
    }
}