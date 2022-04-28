<?php
namespace Core\Drivers;

use Memcached;

class Memcache implements IDriver
{
    protected ?Memcached $client;
    public function __construct(array $parameters = [])
    {
        $this->client = new Memcached();
    }


    function set($key, $value, $lifeTime = 60)
    {
        $this->client->set($key, $value, $lifeTime);
    }

    function remove($key)
    {
        $this->client->delete($key);
    }

    function get($key): string|null
    {
        return $this->client->get($key);
    }

    function all(): array|bool|null
    {
        // TODO not tested
        return $this->client->fetchAll();
    }
}
