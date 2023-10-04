<?php

namespace Supaapps\Supalara\Console\Commands;

use Illuminate\Routing\Console\ControllerMakeCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

use function Laravel\Prompts\confirm;

class CrudControllerMakeCommand extends ControllerMakeCommand
{
    protected $name = 'make:crud-controller';

    protected $description = 'Create a new CRUD controller';

    protected function getStub()
    {
        return __DIR__ . '/../../../stubs/controller.crud.stub';
    }

    protected function getArguments()
    {
        return array_merge(parent::getArguments(), [
            ['model', InputArgument::REQUIRED, 'The CRUD model class (without App\Models\)'],
        ]);
    }

    protected function getOptions()
    {
        return [
            ['shouldPaginate', null, InputOption::VALUE_OPTIONAL, 'Indicates the index should return paginated response', false],
            ['isDeletable', null, InputOption::VALUE_OPTIONAL, 'The model can be deleted', false],
            ['readOnly', null, InputOption::VALUE_OPTIONAL, 'The model is for read only', false],
        ];
    }

    private function getDefaultOption(string $key)
    {
        return array_reduce($this->getOptions(), function ($carry, array $option) use ($key) {
            if ($option[0] === $key) {
                return $option[4];
            }
        }, null);
    }

    protected function buildClass($name)
    {
        $controllerNamespace = $this->getNamespace($name);

        $replace = [];

        if ($this->argument('model')) {
            $replace = $this->buildModelReplacements($replace);
        }

        $replace["use {$controllerNamespace}\Controller;\n"] = '';

        $this->replaceOptionKeys($replace);
        $stub = $this->files->get($this->getStub());

        $parentBuildClass = (string) $this->replaceNamespace($stub, $name)
            ->replaceClass($stub, $name);

        return str_replace(
            array_keys($replace),
            array_values($replace),
            $parentBuildClass
        );
    }

    protected function buildModelReplacements(array $replace)
    {
        $modelClass = $this->parseModel($this->argument('model'));

        if (! class_exists($modelClass) && confirm("A {$modelClass} model does not exist. Do you want to generate it?", default: true)) {
            $this->call('make:model', ['name' => $modelClass]);
        }

        return [
            'DummyFullModelClass' => $modelClass,
            '{{ namespacedModel }}' => $modelClass,
            '{{namespacedModel}}' => $modelClass,
            'DummyModelClass' => class_basename($modelClass),
            '{{ model }}' => class_basename($modelClass),
            '{{model}}' => class_basename($modelClass),
            'DummyModelVariable' => lcfirst(class_basename($modelClass)),
            '{{ modelVariable }}' => lcfirst(class_basename($modelClass)),
            '{{modelVariable}}' => lcfirst(class_basename($modelClass)),
        ];
    }

    private function replaceOptionKeys(&$replace): void
    {
        $this->replaceOnlyIfOptionChanged('shouldPaginate', 'public bool $shouldPaginate', $replace);
        $this->replaceOnlyIfOptionChanged('isDeletable', 'public bool $isDeletable', $replace);
        $this->replaceOnlyIfOptionChanged('readOnly', 'public bool $readOnly', $replace);
    }

    private function replaceOnlyIfOptionChanged(string $key, string $stubLine, array &$replace): void
    {
        if ($this->option($key) == $this->getDefaultOption($key)) {
            $replace["\n    {$stubLine} = {{ {$key} }};\n"] = '';
        } else {
            $replace["{{ {$key} }}"] = var_export($this->option($key), true);
        }
    }

    protected function afterPromptingForMissingArguments(InputInterface $input, OutputInterface $output)
    {
        $input->setOption('shouldPaginate', confirm(
            label: 'Is index should return shouldPaginate response?',
            default: $this->option('shouldPaginate')
        ));

        $input->setOption('isDeletable', confirm(
            label: 'Is the model isDeletable?',
            default: $this->option('isDeletable')
        ));

        $input->setOption('readOnly', confirm(
            label: 'Is the model for read only?',
            default: $this->option('readOnly')
        ));
    }

    protected function promptForMissingArgumentsUsing()
    {
        return array_merge(parent::promptForMissingArgumentsUsing(), [
            'model' => [
                'The CRUD model class (without App\Models\)',
                'E.g. User',
            ],
        ]);
    }
}
