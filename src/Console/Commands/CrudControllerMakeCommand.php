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
            ['paginated', null, InputOption::VALUE_OPTIONAL, 'Indicates the index should return paginated response', false],
            ['deletable', null, InputOption::VALUE_OPTIONAL, 'The model can be deleted', false],
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

        // TODO: This block can be dry
        if ($this->option('paginated') == $this->getDefaultOption('paginated')) {
            $replace["\n    public bool \$shouldPaginate = {{ paginated }};\n"] = '';
        } else {
            $replace["{{ paginated }}"] = var_export($this->option('paginated'), true);
        }
        $replace["{{ deletable }}"] = var_export($this->option('deletable'), true);
        $replace["{{ readOnly }}"] = var_export($this->option('readOnly'), true);

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

    protected function afterPromptingForMissingArguments(InputInterface $input, OutputInterface $output)
    {
        $input->setOption('paginated', confirm(
            label: 'Is index should return paginated response?',
            default: $this->option('paginated')
        ));

        $input->setOption('deletable', confirm(
            label: 'Is the model deletable?',
            default: $this->option('deletable')
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
            'paginated' => [
                'The CRUD model class (without App\Models\)',
                'E.g. User',
            ],
        ]);
    }
}
