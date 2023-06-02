<?php

namespace Mindwingx\ServiceCallAdapter\handlers;

use Mindwingx\ServiceCallAdapter\helpers\Params;

class ServiceGenerator
{
    /**
     * @var string
     */
    public string $serviceName;
    /**
     * @var string
     */
    public string $namespace;
    /**
     * @var string|null
     */
    public ?string $servicePath;
    /**
     * @var string|null
     */
    public ?string $result;

    /**
     * Init properties
     */
    public function __construct()
    {
        $this->servicePath = null;
        $this->result = null;
    }

    /**
     * @param string $serviceName
     * @param string $namespace
     * @return void
     */
    public function generate(string $serviceName, string $namespace = ""): void
    {
        $this->serviceName = ucfirst($serviceName);
        $this->namespace = !empty($namespace) ? $namespace : "App\ServiceCalls";

        $this->makeServiceDir()->makeServiceFiles();
    }

    /**
     * @return $this
     */
    private function makeServiceDir(): self
    {
        $service = sprintf("%s/%s", Params::SERVICE_CALL_DIR, $this->serviceName);
        $this->servicePath = !is_null($this->servicePath)
            ? $this->servicePath . DIRECTORY_SEPARATOR . $service
            : app_path($service);

        if (!is_dir($this->servicePath)) {
            mkdir($this->servicePath, 0755, true);
        }

        if (count(glob($this->servicePath . '/*.php')) > 0) {
            $this->result = "The {$this->serviceName} Service Caller already exists!";
        }

        return $this;
    }

    /**
     * @return void
     */
    private function makeServiceFiles(): void
    {
        if (is_null($this->result)) {
            try {
                $stubs = glob(__DIR__ . '/../../resources/stubs/*.stub');

                foreach ($stubs as $stubPath) {
                    $stub = file_get_contents($stubPath);
                    $stub = str_replace(
                        ['{{dummyNamespace}}', '{{dummyService}}'],
                        [$this->namespace, $this->serviceName],
                        $stub
                    );

                    $className = strpos($stubPath, 'Adapter')
                        ? $this->serviceName . Params::SERVICE_CALL_ADAPTER
                        : $this->serviceName . Params::SERVICE_CALL;

                    $class = sprintf("%s/%s.php", $this->servicePath, $className);
                    file_put_contents($class, $stub);
                }
            } catch (\Exception $exception) {
                $this->result = sprintf('Service file generating failure: %s', $exception->getMessage());
            }
        }
    }
}
