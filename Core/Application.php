<?php
namespace Core;


use Core\Drivers\IDriver;
use Exception;

class Application
{
    protected ?Driver $driver;
    protected string $method;
    protected string|null $key = null;
    protected null|string $value = null;
    static array $config;
    protected bool $isCLI = false;
    protected array|bool $matchedRoute;

    /**
     * @throws Exception
     */
    public function __construct(array $arguments = [], bool $cli = false)
    {
        self::$config = require_once "config/app.php";
        $this->isCLI = $cli;
        if ($cli) {
            $this->bootstrapCli($arguments);
            return;
        }

        $this->bootstrapWeb();
    }

    /**
     * @throws Exception
     */
    protected function bootstrapCli($arguments)
    {
        if (!array_key_exists($arguments[1], self::$config['drivers'])) {
            throw new Exception("Driver not found");
        }

        $this->driver = new Driver(self::$config['drivers'][$arguments[1]], self::$config['parameters'][$arguments[1]] ?? []);

        $this->method = $arguments[2];

        if ($this->method !== IDriver::ALL_METHOD) {
            $this->key = $arguments[3];
        }

        if ($this->method === \Core\Drivers\IDriver::ADD_METHOD) {
            if (!$arguments[4]) {
                throw new Exception("Not valid value");
            }
            $this->value = $arguments[4];
        }
    }

    protected function bootstrapWeb()
    {
        require_once "routes.php";
        $requestPath = parse_url($_SERVER['REQUEST_URI'])['path'];
        $this->matchedRoute = Router::match($requestPath);
    }

    public function run()
    {
        if ($this->isCLI) {
            (new CLI)($this->driver, $this->method, $this->key, $this->value);
            return;
        }

        header('Content-Type: application/json; charset=utf-8');
        if (!$this->matchedRoute) {
            echo json_encode([
                'status' => false,
                'code' => 404,
                'data' => []
            ]);
            die();
        }

        if (is_callable($this->matchedRoute['route']['callback'])) {
            try {
                $data = call_user_func($this->matchedRoute['route']['callback'], ...$this->matchedRoute['arguments']);
                echo json_encode([
                    'status' => true,
                    'code' => 200,
                    'data' => $data
                ]);
            } catch (Exception $exception) {
                echo json_encode([
                    'status' => false,
                    'code' => 500,
                    'data' => [
                        'message' => $exception->getMessage()
                    ]
                ]);
            }
        }
    }
}
