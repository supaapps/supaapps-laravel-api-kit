<?php

namespace Supaapps\LaravelApiKit\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;

use function Laravel\Prompts\confirm;

class InitLaravelApiKit extends Command
{
    protected $signature = 'init:laravel-api-kit';

    protected $description = 'Initialize laravel api kit';

    private ?string $gitRepo;

    public function handle()
    {
        $this->gitRepo = $this->ask("What is git repo url ?");

        $this->publishDockerFile()
            ->publishStartupScript();
        // $this->publishDockerCompose();
        // .env.example
    }

    private function publishDockerFile(): self
    {
        if (
            File::exists(base_path('Dockerfile')) &&
            !confirm("Dockerfile exists. Do you want to override it ?", false)
        ) {
            return $this;
        }

        $content = File::get(__DIR__ . '/../../../stubs/Dockerfile.stub');
        $content = str_replace([
            '{{ imageVersion }}',
            '{{ gitRepo }}',
        ], [
            $this->ask("Enter desired image version", "v2.1"),
            $this->gitRepo,
        ], $content);

        File::put(base_path('Dockerfile'), $content);
        $this->info("`Dockerfile` was generated");

        return $this;
    }

    private function publishStartupScript(): self
    {
        if (
            File::exists(base_path('docker/startup.sh')) &&
            !confirm("`docker/startup.sh` exists. Do you want to override it ?", false)
        ) {
            return $this;
        }

        if (!File::exists(base_path('docker'))) {
            File::makeDirectory(base_path('docker'));
        }

        $content = Http::get(
            "https://raw.githubusercontent.com/supaapps/docker-laravel/main/example/docker/startup.sh"
        )->body();

        File::put(base_path('docker/startup.sh'), $content);
        $this->info("docker/startup.sh script was created successfully");

        return $this;
    }
}
