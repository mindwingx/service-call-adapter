<?php

namespace Mindwingx\ServiceCallAdapter\drivers;

use Exception as ExceptionAlias;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
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
     * @var ResponseInterface|null
     */
    private ?ResponseInterface $response;

    private $exception;

    public function __construct()
    {
        $this->guzzle = new Client();
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
     * @param array $headers
     * @return $this
     */
    public function setHeaders(array $headers = []): self
    {
        $this->headers = $headers;
        return $this;
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
     * @param array $payload
     * @return $this
     */
    public function setQuery(array $payload = []): self
    {
        $this->query = $payload;
        return $this;
    }

    /**
     * @param string $method
     * @return HttpClient
     * @throws GuzzleException
     */
    public function sendRequest(string $method): self
    {
        try {
            $this->response = $this->guzzle->request(
                $method,
                $this->url,
                [
                    'headers' => $this->headers,
                    'json' => $this->body,
                    'query' => $this->query,
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
