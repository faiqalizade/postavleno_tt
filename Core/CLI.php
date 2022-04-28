<?php
namespace Core;

class CLI
{
    public function __invoke(Driver $driver, string $method, string $key = null, string $value = null)
    {
        $_return = $driver->{$method}($key);

        if (is_callable($_return)) {
            $_return($value);
        }

        if ($_return and (is_string($_return) or is_array($_return))) {
            if (is_array($_return)) {
                print_r($_return);
            } else {
                echo "$_return";
            }
            echo "\n";
        }
    }
}
