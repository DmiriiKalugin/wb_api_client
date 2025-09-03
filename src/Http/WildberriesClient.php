<?php

namespace DmitrijKalugin\WildberriesApiClient\Http;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
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

    /**
     * @param array $config
     */
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

    /**
     * @param string $token
     * @return $this
     */
    public function setToken(string $token): self
    {
        $this->token = $token;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getToken(): ?string
    {
        return $this->token;
    }

    /**
     * @throws AuthenticationException
     */
    public function get(string $endpoint, array $params = []): array
    {
        return $this->request('GET', $endpoint, [
            'query' => $params
        ]);
    }

    /**
     * @param string $endpoint
     * @param array $data
     * @return array
     * @throws AuthenticationException
     */
    public function post(string $endpoint, array $data = []): array
    {
        return $this->request('POST', $endpoint, [
            'json' => $data
        ]);
    }

    /**
     * @param string $endpoint
     * @param array $data
     * @return array
     * @throws AuthenticationException
     */
    public function put(string $endpoint, array $data = []): array
    {
        return $this->request('PUT', $endpoint, [
            'json' => $data
        ]);
    }

    /**
     * @param string $endpoint
     * @param array $data
     * @return array
     * @throws AuthenticationException
     */
    public function delete(string $endpoint, array $data = []): array
    {
        return $this->request('DELETE', $endpoint, [
            'json' => $data
        ]);
    }

    /**
     * @param string $service
     * @return array
     * @throws AuthenticationException
     */
    public function ping(string $service = 'common'): array
    {
        $baseUrl = $this->baseUrls[$service] ?? $this->baseUrls['common'];
        return $this->request('GET', '/ping', [], $baseUrl);
    }

    /**
     * @param string $method
     * @param string $endpoint
     * @param array $options
     * @param string|null $baseUrl
     * @return array
     * @throws AuthenticationException
     * @throws RateLimitException
     * @throws WildberriesApiException
     * @throws GuzzleException
     */
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

    /**
     * @param RequestException $e
     * @return void
     * @throws AuthenticationException
     * @throws RateLimitException
     * @throws WildberriesApiException
     */
    protected function handleRequestException(RequestException $e): void
    {
        $response = $e->getResponse();
        $statusCode = $response ? $response->getStatusCode() : 0;
        $body = $response ? $response->getBody()->getContents() : '';
        
        $data = $body ? json_decode($body, true) : [];

        throw match ($statusCode) {
            401 => new AuthenticationException($data['message'] ?? 'Authentication failed'),
            429 => new RateLimitException($data['message'] ?? 'Rate limit exceeded'),
            default => WildberriesApiException::fromResponse($data, $statusCode),
        };
    }

    /**
     * @param string $service
     * @return string
     */
    public function getBaseUrl(string $service): string
    {
        return $this->baseUrls[$service] ?? $this->baseUrls['common'];
    }

    /**
     * @param string $service
     * @param string $method
     * @param string $endpoint
     * @param array $options
     * @return array
     * @throws AuthenticationException
     * @throws GuzzleException
     * @throws RateLimitException
     * @throws WildberriesApiException
     */
    public function requestToService(string $service, string $method, string $endpoint, array $options = []): array
    {
        $baseUrl = $this->getBaseUrl($service);
        return $this->request($method, $endpoint, $options, $baseUrl);
    }

    /**
     * @param array $baseUrls
     * @return $this
     */
    public function setBaseUrls(array $baseUrls): self
    {
        $this->baseUrls = array_merge($this->baseUrls, $baseUrls);
        return $this;
    }
}
