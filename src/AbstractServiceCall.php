<?php

namespace Mindwingx\ServiceCallAdapter;

use Exception;
use Mindwingx\ServiceCallAdapter\helpers\Params;
use Psr\Http\Message\ResponseInterface;

class AbstractServiceCall
{
    /**
     * @var array
     */
    public array $services;
    /**
     * @var string
     */
    public string $namespace;

    /**
     * Init properties
     */
    public function __construct()
    {
        $this->services = $this->detectDirectories(app_path(Params::SERVICE_CALL_DIR));
        $this->namespace = Params::SERVICE_CALL_NAMESPACE;
    }

    /**
     * @param $serviceName
     * @param $arguments
     * @return ResponseInterface|Exception|array
     */
    public function __call($serviceName, $arguments): ResponseInterface|Exception|array
    {
        $service = ucfirst($serviceName);

        if (in_array($service, $this->services)) {
            $serviceInstance = sprintf('%s\%s\%sAdapter', $this->namespace, $service, $service);

            if (class_exists($serviceInstance)) {
                $service = new $serviceInstance();
                return $service->call($arguments[0]);
            }
        }

        throw new \BadMethodCallException("The {$serviceName} service does not exist.");
    }

    // HELPER METHODS

    /**
     * @param string $path
     * @return array
     */
    public static function detectDirectories(string $path): array
    {
        $directoriesNames = [];

        if (!empty($path)) {
            $directoriesPath = glob($path . '/*', GLOB_ONLYDIR);

            foreach ($directoriesPath as $dir) {
                $explode = explode("/", $dir);
                $directoriesNames[] = end($explode);
            }
        }

        return $directoriesNames;
    }
}
