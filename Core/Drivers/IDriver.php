<?php
namespace Core\Drivers;

interface IDriver
{
    const ADD_METHOD = "add";
    const DELETE_METHOD = "delete";
    const GET_METHOD = "get";
    const ALL_METHOD = "all";

    public function __construct(array $parameters = []);

    function get($key): string|null;

    function set($key, $value, $lifeTime = 60);

    function remove($key);

    function all(): array|bool|null;
}
