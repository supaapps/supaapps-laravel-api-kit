<?php

namespace Supaapps\Supalara\Console\Commands;

use Illuminate\Routing\Console\ControllerMakeCommand as Command;

class ControllerMakeCommand extends Command
{
    protected $name = 'make:crud-controller';

    protected $description = 'Create a new CRUD controller';

    protected function resolveStubPath($stub)
    {
        return __DIR__ . '/../../../stubs/controller.crud.stub';
    }
}
