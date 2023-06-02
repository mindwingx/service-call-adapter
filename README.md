# Service Call Adapter

The Service Call Adapter is a Laravel package designed to simplify the integration and communication with various
microservices and third-party services.

### Features

Generate new service calls with a simple command: php artisan sc:new <your-service-name>
Generated service calls are accessible in the app/ServiceCalls directory
Easily set up and configure your services

### Installation

- You can install the Service Call Adapter package via Composer:

```
    composer require mindwingx/service-call-adapter
```

### Usage

- Generate a new service call using the following command:

```
    php artisan sc:new <your-service-name>
```

- To call (the microservices or third-parties) endpoint(service-call), use as the below. 

Note: You have to exactly use the service name of creating after `handle()` method.

```php
<?php

namespace App\DummyNamespace;

use Mindwingx\ServiceCallAdapter\ServiceCall;

class DummyClass
{
    public function DummyMethod()
    {
        try {
            $response = ServiceCall::handle()->yourServiceName(["dummy-payload"]);
        }catch (\Exception $exception){
            return $exception->getMessage();
        }

        return $response;
    }
}
```

### Documentation

To prepare needed data/payload for requests, put them into the `array` in the `yourServiceName` method as above. 
It will be passed through them in related service `ServiceAdapter` and then selected `ServiceCall` class.

##### Service Adapter Class example
- If there are different services with the same purpose, create multiple `ServiceCall` classes instances and handle the in the related ServiceAdapter class Constructor. 
```php
class DummyServiceAdapter implements ServiceCallAdapterInterface
{
    /**
     * @var ServiceCallInterface
     */
    private ServiceCallInterface $service;

    public function __construct()
    {
        /*
         * Note: you make multiple Service Call Classes and handle them here to
         * access by related condition or etc.
         */

        $this->service = new FirstServiceCall();
    }

    /**
     * @throws GuzzleException
     */
    public function call(array $payload = []): ResponseInterface|array
    {
        return $this->service
            ->preparePayload($payload)
            ->getResult();
    }
}
```

##### Service Call Class example

- The GuzzleHttp is used as the http driver. In `getResult()` method, it returns the request response as `array`. You can replace `getArrayResponse()` with `getResponse()` to get default Guzzle response.

```php
class FirstServiceCall extends ServiceCallHandler
{

    public function preparePayload(array $payload = []): self
    {
        //todo: set service call details
        $this->setUrl('https://service-url.io/api')
             ->setQuery([])
             ->setHeaders([])
             ->setBody($payload);

        return $this;
    }

    /**
     * @throws GuzzleException
     */
    public function getResult(): ResponseInterface|array
    {
        return $this->sendRequest(Http::GET)
                    ->getArrayResponse();
    }
}
```

- The generated service call will be available in the app/ServiceCalls directory.

```
       app
        |
        |____ ServiceCalls
        |           |
        |           |____ firstService
        |           |         |
        |           |         |____ ServiceAdapter
        |           |         |
        |           |         |____ FirstServiceCall
        |           |         |
        |           |         |____ SecondServiceCall
        |           |         |
        |           |         |.....
        |           |
        |           |____ secondService
        |
        |
        |.....    

```

- Customize and configure your services according to your specific needs.

### Contributing

Contributions are welcome! If you find any issues or have suggestions for improvement, please submit an issue or a pull
request on the GitHub repository.

### License

The Service Call Adapter package is open-source software licensed under the MIT license.

### Credits

The Service Call Adapter package is developed and maintained by Milad Roudgarian.
