<?php

namespace Supaapps\LaravelApiKit\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Encryption\Encrypter;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;

use function Laravel\Prompts\confirm;

class InitLaravelApiKit extends Command
{
    protected $signature = 'init:laravel-api-kit';

    protected $description = 'Initialize laravel api kit';

    private ?string $gitRepo;

    private ?string $dbName = null;

    private ?string $dbPassword;

    public function handle()
    {
        $this->gitRepo = $this->ask('What is git repo url ?');

        $this->publishDockerFile()
            ->publishStartupScript()
            ->createDockerCompose()
            ->publishEnv();
    }

    private function publishDockerFile(): self
    {
        if (
            File::exists(base_path('Dockerfile')) &&
            !confirm('Dockerfile exists. Do you want to override it ?', false)
        ) {
            return $this;
        }

        $content = File::get(__DIR__ . '/../../../stubs/Dockerfile.stub');
        $content = str_replace([
            '{{ imageVersion }}',
            '{{ gitRepo }}',
        ], [
            $this->ask('Enter desired image version', 'v2.1'),
            $this->gitRepo,
        ], $content);

        File::put(base_path('Dockerfile'), $content);
        $this->info('`Dockerfile` was generated');

        return $this;
    }

    private function publishStartupScript(): self
    {
        if (
            File::exists(base_path('docker/startup.sh')) &&
            !confirm('`docker/startup.sh` exists. Do you want to override it ?', false)
        ) {
            return $this;
        }

        if (!File::exists(base_path('docker'))) {
            File::makeDirectory(base_path('docker'));
        }

        $content = Http::get(
            'https://raw.githubusercontent.com/supaapps/docker-laravel/main/example/docker/startup.sh'
        )->body();

        File::put(base_path('docker/startup.sh'), $content);
        $this->info('docker/startup.sh script was created successfully');

        return $this;
    }

    private function createDockerCompose(): self
    {
        if (
            File::exists(base_path('docker-compose.yml')) &&
            !confirm('`docker-compose.yml` exists. Do you want to override it ?', false)
        ) {
            return $this;
        }

        $content = <<<YML
version: '3.9'
services:
YML;

        if (confirm('Do you want mysql database docker image?')) {
            $this->dbName = $this->ask('Enter database name', Str::random());
            $this->dbPassword = $this->ask('Enter database password', Str::random());

            $content .= <<<YML

    db:
        image: mysql:8.0.33
        command: --default-authentication-plugin=mysql_native_password
        restart: "always"
        environment:
            MYSQL_DATABASE: {$this->dbName}
            MYSQL_ROOT_PASSWORD: {$this->dbPassword}
        healthcheck:
            test: ["CMD", "mysqladmin" ,"ping", "-h", "localhost"]
            interval: 1s
            timeout: 2s
            retries: 10
YML;
        }

        if (confirm('Do you want redis docker image?')) {
            $content .= <<<YML

    redis:
        image: redis:7.0.11
        restart: "always"
        healthcheck:
            test: ["CMD", "redis-cli", "ping"]
            interval: 1s
            timeout: 2s
            retries: 10
YML;
        }

        $content .= <<<YML

    api:
        build:
            context: .
        ports:
            - 8080:80
        volumes:
            - ./:/var/www
            - ./vendor:/var/www/vendor:ro,cached
        entrypoint: '/var/www/docker/startup.sh'
        restart: "always"
        depends_on:
            db:
                condition: service_healthy
            redis:
                condition: service_healthy
YML;

        if (confirm('Do you want to run queue?')) {
            $content .= <<<YML

    queue:
        build:
            context: .
        entrypoint: "php /var/www/artisan queue:work --tries=5"
        volumes:
            - ./:/var/www
        depends_on:
            - api
YML;
        }

        if (confirm('Do you want to run cron?')) {
            $content .= <<<YML

    cron:
        build:
            context: .
        entrypoint: "cron -f -l"
        volumes:
            - ./:/var/www
        depends_on:
            - api
YML;
        }

        File::put(base_path('docker-compose.yml'), $content);
        $this->info('docker-compose.yml script was created successfully');

        return $this;
    }

    public function publishEnv(): self
    {
        if (
            File::exists(base_path('.env')) &&
            !confirm('.env exists. Do you want to override it ?', false)
        ) {
            return $this;
        }

        $appKey = 'base64:' . base64_encode(
            Encrypter::generateKey($this->laravel['config']['app.cipher'])
        );
        $dbHost = $this->ask(
            'What is database host ?',
            is_null($this->dbName) ? '127.0.0.1' : 'host.docker.internal'
        );
        $content = File::get(__DIR__ . '/../../../stubs/.env.stub');
        $content = str_replace([
            '{{ appName }}',
            '{{ appKey }}',
            '{{ appUrl }}',
            '{{ dbHost }}',
            '{{ dbName }}',
            '{{ dbPassword }}',
        ], [
            $this->ask('What is app name ?', 'Laravel'),
            $appKey,
            $this->ask('What is app url ?', 'http://localhost'),
            $dbHost,
            $this->dbName ?? $this->ask("Enter database name", Str::random()),
            $this->dbPassword ?? $this->ask("Enter database password", Str::random()),
        ], $content);

        File::put(base_path('.env'), $content);
        $this->info('`.env` was generated');

        return $this;
    }
}
