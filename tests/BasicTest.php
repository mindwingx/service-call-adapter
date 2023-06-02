<?php

namespace Mindwingx\ServiceCallAdapter\Tests;

use Mindwingx\ServiceCallAdapter\handlers\ServiceGenerator;
use Mindwingx\ServiceCallAdapter\ServiceCall;
use Mockery;

class BasicTest extends TestCase
{
    /**
     * @test
     * @return void
     */
    public function service_call_instance(): void
    {
        $serviceCall = Mockery::mock(ServiceCall::class);
        $serviceCall->shouldReceive('handle')->andReturnSelf();

        $this->assertInstanceOf(ServiceCall::class, $serviceCall);
    }

    /**
     * @test
     * @return void
     */
    public function service_call_with_wrong_service_name(): void
    {
        try {
            ServiceCall::handle()->dummyService();
            // $this->fail('Expected exception was not thrown.');
        } catch (\BadMethodCallException $exception) {
            $this->assertInstanceOf(\BadMethodCallException::class, $exception);
            $this->assertEquals('The dummyService service does not exist.', $exception->getMessage());
        }
    }

    /**
     * @test
     * @return void
     */
    public function service_call_with_correct_service_name(): void
    {
        $testNamespace = 'Mindwingx\ServiceCallAdapter\Tests\ServiceCalls';

        try {
            $this->serviceGenerator("dummyService", $testNamespace);
            $service = ServiceCall::handle();
            $service->services = $service->detectDirectories("./tests/ServiceCalls");
            $service->namespace = "\\" . $testNamespace;
            $response = $service->dummyService();

            $this->assertEquals(200, $response->getStatusCode());

            $services = ServiceCall::detectDirectories("./tests/ServiceCalls");
            $this->assertCount(1, $services);

            $servicesPath = explode('/', glob("./tests/ServiceCalls/*", GLOB_ONLYDIR)[0]);
            $testServices[] = end($servicesPath);
            $this->assertContains("DummyService", $testServices);

            $this->removeTestDirectory("DummyService/");
            // $this->fail('Expected exception was not thrown.');
        } catch (\BadMethodCallException $exception) {
            $this->assertInstanceOf(\BadMethodCallException::class, $exception);
            $this->assertEquals('The dummyService service does not exist.', $exception->getMessage());
        }
    }

    /**
     * @test
     * @return void
     */
    public function service_generator_with_successful_result(): void
    {
        $this->serviceGenerator("dummy");
        $services = ServiceCall::detectDirectories("./tests/ServiceCalls");
        $this->assertCount(1, $services);

        $servicesPath = explode('/', glob("./tests/ServiceCalls/*", GLOB_ONLYDIR)[0]);
        $testServices[] = end($servicesPath);
        $this->assertContains("Dummy", $testServices);
        $this->removeTestDirectory("Dummy/");
    }

    // HELPER METHOdS

    /**
     * @param string $serviceName
     * @param string $namespace
     * @return void
     */
    public function serviceGenerator(string $serviceName, string $namespace = ""): void
    {
        $serviceGenerator = new ServiceGenerator();
        $serviceGenerator->servicePath = "./tests";
        $serviceGenerator->generate($serviceName, $namespace);
    }

    /**
     * @param string $dirName
     * @return void
     */
    public function removeTestDirectory(string $dirName): void
    {
        $dir = "./tests/ServiceCalls/";
        $files = array_diff(scandir($dir . $dirName), ['.', '..']);
        foreach ($files as $file) {
            unlink($dir . $dirName . $file);
        }
        rmdir($dir . $dirName);
        rmdir($dir);
    }
}
