<?php

namespace Supaapps\Supalara\Console\Commands;

use Illuminate\Routing\Console\ControllerMakeCommand;
use Illuminate\Support\Facades\Schema;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

use function Laravel\Prompts\confirm;
use function Laravel\Prompts\select;

class CrudControllerMakeCommand extends ControllerMakeCommand
{
    protected $name = 'make:crud-controller';

    protected $description = 'Create a new CRUD controller';

    private array $tableColumns = [];

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
            ['searchField', null, InputOption::VALUE_OPTIONAL, 'Search by single column', null],
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
        $this->replaceOnlyIfOptionChanged('searchField', 'public ?string $searchField', $replace);
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
        $this->setModelColumns();

        $input->setOption(
            'shouldPaginate',
            confirm('Is index should return paginated response?', $this->option('shouldPaginate'))
        );

        $input->setOption(
            'isDeletable',
            confirm('Is the model can be deleted?', $this->option('isDeletable'))
        );

        $input->setOption(
            'readOnly',
            confirm('Is the model for read only?', $this->option('readOnly'))
        );

        if (is_null($this->option('searchField')) && !empty($this->tableColumns)) {
            $searchByField = confirm("Do yo want to search by single column?", false);

            if ($searchByField) {
                $input->setOption('searchField', select(
                    'Enter column name to search with:',
                    $this->tableColumns,
                    $this->option('searchField')
                ));
            }
        }

    }

    private function setModelColumns(): void
    {
        $modelClass = $this->parseModel($this->argument('model'));

        if (class_exists($modelClass)) {
            $table = app($modelClass)->getTable();
            $this->tableColumns = Schema::getColumnListing($table);
        }
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
