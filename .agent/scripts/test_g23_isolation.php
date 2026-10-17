<?php

namespace Illuminate\Support {
    class ServiceProvider
    {
        public function __construct(public object $app) {}
    }
}

namespace {
    use App\Support\RuntimeIsolation;

    $root = dirname(__DIR__, 2);
    $checks = [];
    $configurations = [];

    function env(string $key, mixed $default = null): mixed
    {
        $value = getenv($key);

        return $value === false ? $default : $value;
    }

    function storage_path(string $path = ''): string
    {
        return dirname(__DIR__, 2).'/storage/'.$path;
    }

    function config(string $key): array
    {
        return $GLOBALS['configurations'][$key];
    }

    function check(string $name, bool $condition): void
    {
        $GLOBALS['checks'][$name] = $condition;
        if (! $condition) {
            throw new RuntimeException($name);
        }
    }

    function rejected(array $configuration): bool
    {
        try {
            RuntimeIsolation::assertConfiguration($configuration);
        } catch (LogicException) {
            return true;
        }

        return false;
    }

    foreach (['DB_CONNECTION', 'DB_HOST', 'DB_PORT', 'DB_DATABASE', 'DB_USERNAME', 'DB_PASSWORD', 'DB_URL', 'APP_BASE_PATH', 'LARAVEL_STORAGE_PATH'] as $key) {
        putenv($key);
    }
    require $root.'/app/Support/RuntimeIsolation.php';
    foreach (['database', 'cache', 'queue', 'session', 'auth', 'filesystems', 'mail', 'logging', 'cors', 'services', 'app'] as $name) {
        $configurations[$name] = require $root.'/config/'.$name.'.php';
    }
    RuntimeIsolation::assertConfiguration($configurations);
    check('default_configuration_accepted', true);
    check('only_mysql_connection', array_keys($configurations['database']['connections']) === ['mysql']);
    check('dedicated_database_name', $configurations['database']['connections']['mysql']['database'] === 'mnm_vat_tu_nong_nghiep');
    foreach (['pgsql', 'mongodb', 'sqlite', 'sqlsrv'] as $driver) {
        $candidate = $configurations;
        $candidate['database']['default'] = $driver;
        check('reject_driver_'.$driver, rejected($candidate));
    }
    foreach (['database' => 'klcn186_dev', 'host' => '192.0.2.1', 'url' => 'mysql://source.invalid', 'unix_socket' => '/tmp/other.sock', 'charset' => 'utf8', 'engine' => 'MyISAM', 'timezone' => '+07:00'] as $key => $value) {
        $candidate = $configurations;
        $candidate['database']['connections']['mysql'][$key] = $value;
        check('reject_mysql_'.$key, rejected($candidate));
    }
    foreach (['session' => 'driver', 'cache' => 'default', 'queue' => 'default'] as $group => $key) {
        $candidate = $configurations;
        $candidate[$group][$key] = 'database';
        check('reject_database_'.$group, rejected($candidate));
    }
    foreach (['cookie' => 'klcn_session', 'domain' => '.source.invalid', 'files' => dirname($root).'/SourceCode/api-laravel/storage'] as $key => $value) {
        $candidate = $configurations;
        $candidate['session'][$key] = $value;
        check('reject_shared_session_'.$key, rejected($candidate));
    }
    foreach (['DB_URL' => 'pgsql://source.invalid', 'APP_BASE_PATH' => dirname($root), 'LARAVEL_STORAGE_PATH' => dirname($root)] as $key => $value) {
        putenv($key.'='.$value);
        check('reject_environment_'.$key, rejected($configurations));
        putenv($key);
    }
    putenv('DB_DATABASE=klcn186_dev');
    $candidate = $configurations;
    $candidate['database'] = require $root.'/config/database.php';
    check('reject_stale_database_environment', rejected($candidate));
    putenv('DB_DATABASE');
    foreach (['migrate', 'db:seed', 'migrate:fresh', 'queue:work', 'schedule:run', 'alerts:scan', 'klcn:create-user', 'tinker', 'config:cache'] as $command) {
        check('command_denied_'.$command, ! RuntimeIsolation::allowsCommand(['artisan', $command]));
    }
    check('route_list_allowed', RuntimeIsolation::allowsCommand(['artisan', 'route:list']));
    check('extra_arguments_denied', ! RuntimeIsolation::allowsCommand(['artisan', 'list', 'migrate']));
    $manager = new class {
        public array $extensions = [];

        public function extend(string $driver, callable $factory): void
        {
            $this->extensions[$driver] = $factory;
        }
    };
    $container = new class($manager) {
        public function __construct(private object $manager) {}

        public function make(string $name): object
        {
            if ($name !== 'db') {
                throw new RuntimeException('Unexpected binding');
            }

            return $this->manager;
        }
    };
    require $root.'/app/Providers/AppServiceProvider.php';
    (new App\Providers\AppServiceProvider($container))->register();
    check('provider_registered_mysql_deny_factory', isset($manager->extensions['mysql']));
    try {
        ($manager->extensions['mysql'])();
        check('provider_rejects_connection', false);
    } catch (LogicException $error) {
        check('provider_rejects_connection', str_contains($error->getMessage(), 'chưa cho phép'));
    }
    ob_start();
    require $root.'/public/index.php';
    $body = ob_get_clean();
    check('http_entrypoint_returns_503', http_response_code() === 503 && str_contains($body, 'runtime nghiệp vụ đang khóa'));
    echo json_encode(['status' => 'PASS', 'checks' => $checks, 'assertions' => count($checks), 'limits' => ['ServiceProvider/DB manager boundary uses test doubles; no real Laravel boot or database driver invoked']], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT).PHP_EOL;
}
