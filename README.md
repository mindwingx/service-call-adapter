# Service Call Adapter

The Service Call Adapter is a Laravel package designed to simplify the integration and communication with various microservices and third-party services.

### Features

- Generate new service calls with a simple command
- Easily set up and configure your services
- Allocate multiple services by customized condition
- Generated service calls are easy to access in the `app/ServiceCalls` directory

### Installation

- You can install the Service Call Adapter package via Composer:

```composer log
composer require mindwingx/service-call-adapter
```

### Usage

- Generate a new service call using the following command:

```php
php artisan sc:new <your-service-name>
```

- To call the microservices or third-party endpoints, you can use the generated service calls. For example:

```php
use Mindwingx\ServiceCallAdapter\ServiceCall;

try {
    $response = ServiceCall::handle()->yourServiceName(["dummy-payload"]);
} catch (\Exception $exception) {
    return $exception->getMessage();
}

return $response;

```

** Note: You have to strictly use the service name of creating after the `handle()` method.

** Customize and configure your services according to your specific needs.

### Documentation

To prepare the needed data/payload for requests, pass them as an array in the `yourServiceName` method. The payload will be passed to the related service via the `ServiceAdapter` and `ServiceCall` classes.

##### Service Adapter Class example

- You can create multiple instances of the `ServiceCall` class in the related `ServiceAdapter` class if you have different services with the same purpose. Handle them in the constructor of the ServiceAdapter class based on your conditions.

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
         * access by the related condition, etc.
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

- The GuzzleHttp is used as the HTTP driver. In the `getResult()` method, it returns the request-response as an `array`. You can replace `getArrayResponse()` with `getResponse()` to get the default Guzzle response.

```php
class FirstServiceCall extends ServiceCallHandler
{

    public function preparePayload(array $payload = []): self
    {
        //todo: set service call details
        $this->setUrl('https://service-url.io/api')
             ->setMethod() //  default: GET
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
        return $this->sendRequest()
                    ->getArrayResponse();
    }
}
```

- The generated service call will be available in the app/ServiceCalls directory.

### Contributing

Contributions are welcome! If you find any issues or have suggestions for improvement, please submit an issue or a pull
request on the GitHub repository.

### License

The Service Call Adapter package is open-source software licensed under the MIT license.

### Credits

The Service Call Adapter package is developed and maintained by Milad Roudgarian.
