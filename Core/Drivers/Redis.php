<?php
namespace Core\Drivers;

use Predis\Client;

class Redis implements IDriver
{
    protected ?Client $client;
    public function __construct(array $parameters = [])
    {
        $this->client = new Client($parameters);
    }

    public function get($key): string|null
    {
        return $this->client->get($key);
    }

    public function set($key, $value, $lifeTime = 60)
    {
        $this->client->setex($key, $lifeTime, $value);
    }

    public function remove($key): int
    {
        return $this->client->del($key);
    }

    function all(): array|bool|null
    {
        $keys = $this->client->keys("*");
        if (!$keys) {
            return false;
        }
        $_return = [];

        foreach ($keys as $key) {
            $_return[$key] = $this->client->get($key);
        }

        return $_return;
    }
}
