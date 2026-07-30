<?php

declare(strict_types=1);

namespace App\Support;

use App\Services\TodoService;

trait ServiceAccessTrait
{
    private static array $serviceCache = [];

    /**
     * @param string $fqdn
     * @param array $parameters
     * @return mixed
     */
    public static function resolveService(string $fqdn, array $parameters = []): object
    {
        $key = $fqdn . (!empty($parameters) ? json_encode(array_keys($parameters)) : '');

        if (!isset(self::$serviceCache[$key])) {
            self::$serviceCache[$key] = resolve($fqdn, $parameters);
        }

        return self::$serviceCache[$key];
    }

    /**
     * @param string $fqdn
     * @param array $parameters
     * @return mixed
     */
    final protected function getService(string $fqdn, array $parameters = []): object
    {
        return static::resolveService($fqdn, $parameters);
    }

    /**
     * @return TodoService
     */
    final protected function todoService(): TodoService
    {
        return static::resolveService(TodoService::class);
    }
}
