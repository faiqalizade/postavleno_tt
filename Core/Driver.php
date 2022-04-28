<?php
namespace Core;

use Core\Drivers\IDriver;
use Core\Drivers\Redis;

class Driver
{
    protected ?IDriver $client;

    public function __construct($driver = Redis::class, array $parameters = [])
    {
        $this->client = new $driver($parameters);
    }

    public function add(string $key): \Closure
    {
        return function ($value) use ($key) {
            $this->client->set($key, $value);
        };
    }

    public function delete(string|array $key)
    {
        return $this->client->remove($key);
    }

    public function get(string $key): string|null
    {
        return $this->client->get($key);
    }


    public function all()
    {
        return $this->client->all();
    }
}
