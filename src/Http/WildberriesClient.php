<?php

namespace DmitrijKalugin\WildberriesApiClient\Http;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use DmitrijKalugin\WildberriesApiClient\Contracts\WildberriesClientInterface;
use DmitrijKalugin\WildberriesApiClient\Exceptions\AuthenticationException;
use DmitrijKalugin\WildberriesApiClient\Exceptions\RateLimitException;
use DmitrijKalugin\WildberriesApiClient\Exceptions\WildberriesApiException;

class WildberriesClient implements WildberriesClientInterface
{
    protected Client $httpClient;
    protected ?string $token = null;
    protected array $baseUrls = [
        'common' => 'https://common-api.wildberries.ru',
        'content' => 'https://content-api.wildberries.ru',
        'marketplace' => 'https://marketplace-api.wildberries.ru',
        'statistics' => 'https://statistics-api.wildberries.ru',
        'advert' => 'https://advert-api.wildberries.ru',
        'feedbacks' => 'https://feedbacks-api.wildberries.ru',
        'chat' => 'https://buyer-chat-api.wildberries.ru',
        'supplies' => 'https://supplies-api.wildberries.ru',
        'returns' => 'https://returns-api.wildberries.ru',
        'documents' => 'https://documents-api.wildberries.ru',
        'finance' => 'https://finance-api.wildberries.ru',
    ];

    public function __construct(array $config = [])
    {
        $this->httpClient = new Client(array_merge([
            'timeout' => 30,
            'headers' => [
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ]
        ], $config));

        if (isset($config['token'])) {
            $this->setToken($config['token']);
        }
    }

    public function setToken(string $token): self
    {
        $this->token = $token;
        return $this;
    }

    public function getToken(): ?string
    {
        return $this->token;
    }

    public function get(string $endpoint, array $params = []): array
    {
        return $this->request('GET', $endpoint, [
            'query' => $params
        ]);
    }

    public function post(string $endpoint, array $data = []): array
    {
        return $this->request('POST', $endpoint, [
            'json' => $data
        ]);
    }

    public function put(string $endpoint, array $data = []): array
    {
        return $this->request('PUT', $endpoint, [
            'json' => $data
        ]);
    }

    public function delete(string $endpoint, array $data = []): array
    {
        return $this->request('DELETE', $endpoint, [
            'json' => $data
        ]);
    }

    public function ping(string $service = 'common'): array
    {
        $baseUrl = $this->baseUrls[$service] ?? $this->baseUrls['common'];
        return $this->request('GET', '/ping', [], $baseUrl);
    }

    protected function request(string $method, string $endpoint, array $options = [], ?string $baseUrl = null): array
    {
        if (!$this->token) {
            throw new AuthenticationException('API token is required');
        }

        $url = ($baseUrl ?? $this->baseUrls['common']) . $endpoint;

        $options = array_merge_recursive($options, [
            'headers' => [
                'Authorization' => $this->token,
            ]
        ]);

        try {
            $response = $this->httpClient->request($method, $url, $options);
            $body = $response->getBody()->getContents();
            
            return $body ? json_decode($body, true) : [];
        } catch (RequestException $e) {
            $this->handleRequestException($e);
        }
    }

    protected function handleRequestException(RequestException $e): void
    {
        $response = $e->getResponse();
        $statusCode = $response ? $response->getStatusCode() : 0;
        $body = $response ? $response->getBody()->getContents() : '';
        
        $data = $body ? json_decode($body, true) : [];

        switch ($statusCode) {
            case 401:
                throw new AuthenticationException($data['message'] ?? 'Authentication failed');
            case 429:
                throw new RateLimitException($data['message'] ?? 'Rate limit exceeded');
            default:
                throw WildberriesApiException::fromResponse($data, $statusCode);
        }
    }

    /**
     * Get base URL for specific service
     */
    public function getBaseUrl(string $service): string
    {
        return $this->baseUrls[$service] ?? $this->baseUrls['common'];
    }

    /**
     * Make request to specific service
     */
    public function requestToService(string $service, string $method, string $endpoint, array $options = []): array
    {
        $baseUrl = $this->getBaseUrl($service);
        return $this->request($method, $endpoint, $options, $baseUrl);
    }

    /**
     * Set base URLs for services
     */
    public function setBaseUrls(array $baseUrls): self
    {
        $this->baseUrls = array_merge($this->baseUrls, $baseUrls);
        return $this;
    }
}
