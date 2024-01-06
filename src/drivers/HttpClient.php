<?php

namespace Mindwingx\ServiceCallAdapter\drivers;

use Exception as ExceptionAlias;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Mindwingx\ServiceCallAdapter\helpers\Http;
use Psr\Http\Message\ResponseInterface;

class HttpClient
{
    /**
     * @var Client
     */
    private Client $guzzle;

    /**
     * @var string
     */
    private string $url;

    /**
     * @var array
     */
    private array $headers;

    /**
     * @var array
     */
    private array $body;

    /**
     * @var array
     */
    private array $query;

    /**
     * @var string
     */
    private string $method = Http::GET;

    /**
     * @var ResponseInterface|null
     */
    private ?ResponseInterface $response;

    private $exception;

    public function __construct()
    {
        $this->guzzle = new Client();
    }


    /**
     * @return string
     */
    public function getUrl(): string
    {
        return $this->url;
    }

    /**
     * @param string $url
     * @return $this
     */
    public function setUrl(string $url): self
    {
        $this->url = $url;
        return $this;
    }

    /**
     * @return array
     */
    public function getHeaders(): array
    {
        return $this->headers;
    }

    /**
     * @param array $headers
     * @return $this
     */
    public function setHeaders(array $headers = []): self
    {
        $this->headers = $headers;
        return $this;
    }

    /**
     * @return array
     */
    public function getBody(): array
    {
        return $this->body;
    }

    /**
     * @param array $payload
     * @return $this
     */
    public function setBody(array $payload = []): self
    {
        $this->body = $payload;
        return $this;
    }

    /**
     * @return array
     */
    public function getQuery(): array
    {
        return $this->query;
    }

    /**
     * @param array $payload
     * @return $this
     */
    public function setQuery(array $payload = []): self
    {
        $this->query = $payload;
        return $this;
    }

    /**
     * @return string
     */
    public function getMethod(): string
    {
        return $this->method;
    }

    /**
     * @param string $method
     * @return $this
     */
    public function setMethod(string $method = Http::GET): self
    {
        $this->method = $method;
        return $this;
    }

    /**
     * @return HttpClient
     * @throws GuzzleException
     */
    public function sendRequest(): self
    {
        try {
            $this->response = $this->guzzle->request(
                $this->getMethod(),
                $this->getUrl(),
                [
                    'headers' => $this->getHeaders(),
                    'json' => $this->getBody(),
                    'query' => $this->getQuery(),
                ]
            );
        } catch (ExceptionAlias $exception) {
            $this->exception = $exception;
        }

        return $this;
    }

    /**
     * @return ResponseInterface
     */
    public function getResponse(): ResponseInterface
    {
        return $this->response;
    }

    /**
     * @return array
     */
    public function getArrayResponse(): array
    {
        $response = [
            'status' => $this->response->getStatusCode(),
            'body' => json_decode($this->response->getBody()->getContents(), true),
            'error' => [],
        ];

        if ($this->exception instanceof ExceptionAlias) {
            $response['error'] = [
                'code' => $this->exception->getCode(),
                'message' => $this->exception->getMessage(),
                'trace' => $this->exception->getTraceAsString(),
            ];
        }

        return $response;
    }
}
