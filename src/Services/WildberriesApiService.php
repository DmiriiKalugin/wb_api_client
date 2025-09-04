<?php

namespace DmitrijKalugin\WildberriesApiClient\Services;

use DmitrijKalugin\WildberriesApiClient\Contracts\WildberriesClientInterface;

class WildberriesApiService
{
    protected WildberriesClientInterface $client;

    /**
     * @param WildberriesClientInterface $client
     * @return void
     */
    public function __construct(WildberriesClientInterface $client)
    {
        $this->client = $client;
    }

    /**
     * @param string $token
     * @return $this
     */
    public function setToken(string $token): self
    {
        $this->client->setToken($token);
        return $this;
    }

    /**
     * @return string|null
     */
    public function getToken(): ?string
    {
        return $this->client->getToken();
    }

    /**
     * @param string $service
     * @return array
     */
    public function ping(string $service = 'common'): array
    {
        return $this->client->ping($service);
    }

    /**
     * @return array
     */
    public function getSellerInfo(): array
    {
        return $this->client->get('/api/v1/seller-info');
    }

    /**
     * @param array $params
     * @return array
     */
    public function getNews(array $params = []): array
    {
        return $this->client->get('/api/communications/v2/news', $params);
    }

    /**
     * @param string $endpoint
     * @param array $params
     * @return array
     */
    public function get(string $endpoint, array $params = []): array
    {
        return $this->client->get($endpoint, $params);
    }

    /**
     * @param string $endpoint
     * @param array $data
     * @return array
     */
    public function post(string $endpoint, array $data = []): array
    {
        return $this->client->post($endpoint, $data);
    }

    /**
     * @param string $endpoint
     * @param array $data
     * @return array
     */
    public function put(string $endpoint, array $data = []): array
    {
        return $this->client->put($endpoint, $data);
    }

    /**
     * @param string $endpoint
     * @param array $data
     * @return array
     */
    public function delete(string $endpoint, array $data = []): array
    {
        return $this->client->delete($endpoint, $data);
    }

    /**
     * @param string $service
     * @param string $method
     * @param string $endpoint
     * @param array $options
     * @return array
     */
    public function requestToService(string $service, string $method, string $endpoint, array $options = []): array
    {
        return $this->client->requestToService($service, $method, $endpoint, $options);
    }

    // Specific API methods based on Wildberries API documentation

    /**
     * Получение списка карточек товаров с поддержкой пагинации
     *
     * @param array $settings Настройки запроса включая cursor и filter
     * @param bool $getAllPages Получить все страницы автоматически (по умолчанию false)
     * @return array
     */
    public function getCards(array $settings = [], bool $getAllPages = false): array
    {
        $defaultSettings = [
            'cursor' => [
                'limit' => 100
            ],
            'filter' => [
                'withPhoto' => -1
            ]
        ];

        $requestSettings = array_merge_recursive($defaultSettings, $settings);

        $payload = [
            'settings' => $requestSettings
        ];

        if (!$getAllPages) {
            return $this->client->requestToService('content', 'POST', '/content/v2/get/cards/list', ['json' => $payload]);
        }

        $allCards = [];
        $totalReceived = 0;

        do {
            $response = $this->client->requestToService('content', 'POST', '/content/v2/get/cards/list', ['json' => $payload]);

            if (isset($response['data']) && is_array($response['data'])) {
                $allCards = array_merge($allCards, $response['data']);
                $totalReceived += count($response['data']);
            }

            if (isset($response['cursor']['updatedAt']) && isset($response['cursor']['nmID'])) {
                $payload['settings']['cursor']['updatedAt'] = $response['cursor']['updatedAt'];
                $payload['settings']['cursor']['nmID'] = $response['cursor']['nmID'];
            } else {
                break;
            }

            $total = $response['total'] ?? 0;

        } while ($total >= $requestSettings['cursor']['limit']);

        return [
            'data' => $allCards,
            'total' => $totalReceived,
            'error' => false,
            'errorText' => '',
            'additionalErrors' => null
        ];
    }

    /**
     * @param array $cards
     * @return array
     */
    public function updateCards(array $cards): array
    {
        return $this->client->requestToService('content', 'POST', '/content/v2/cards/update', ['json' => $cards]);
    }

    /**
     * @param int $nmId
     * @return array
     */
    public function getMediaFiles(int $nmId): array
    {
        return $this->client->requestToService('content', 'GET', "/content/v2/media/{$nmId}");
    }

    /**
     * Marketplace API Methods
     */

    /**
     * @return array
     */
    public function getWarehouses(): array
    {
        return $this->client->requestToService('marketplace', 'GET', '/api/v3/warehouses');
    }

    /**
     * @param array $params
     * @return array
     */
    public function getOrders(array $params = []): array
    {
        return $this->client->requestToService('marketplace', 'GET', '/api/v3/orders', ['query' => $params]);
    }

    /**
     * @param array $params
     * @return array
     */
    public function getStocks(array $params = []): array
    {
        return $this->client->requestToService('marketplace', 'GET', '/api/v3/stocks', ['query' => $params]);
    }

    /**
     * @param array $stocks
     * @return array
     */
    public function updateStocks(array $stocks): array
    {
        return $this->client->requestToService('marketplace', 'PUT', '/api/v3/stocks', ['json' => ['stocks' => $stocks]]);
    }

    /**
     * Statistics API Methods
     */

    /**
     * @param array $params
     * @return array
     */
    public function getIncomes(array $params = []): array
    {
        return $this->client->requestToService('statistics', 'GET', '/api/v1/supplier/incomes', ['query' => $params]);
    }

    /**
     * @param array $params
     * @return array
     */
    public function getSales(array $params = []): array
    {
        return $this->client->requestToService('statistics', 'GET', '/api/v1/supplier/sales', ['query' => $params]);
    }

    /**
     * @param array $params
     * @return array
     */
    public function getStockStatistics(array $params = []): array
    {
        return $this->client->requestToService('statistics', 'GET', '/api/v1/supplier/stocks', ['query' => $params]);
    }

    /**
     * Advertising API Methods
     */

    /**
     * @return array
     */
    public function getAdvertCampaigns(): array
    {
        return $this->client->requestToService('advert', 'GET', '/adv/v1/promotion/count');
    }

    /**
     * Feedbacks API Methods
     */

    /**
     * @param array $params
     * @return array
     */
    public function getFeedbacks(array $params = []): array
    {
        return $this->client->requestToService('feedbacks', 'GET', '/api/v1/feedbacks', ['query' => $params]);
    }

    /**
     * Finance API Methods
     */

    /**
     * @param array $params
     * @return array
     */
    public function getFinanceReports(array $params = []): array
    {
        return $this->client->requestToService('finance', 'GET', '/api/v1/supplier/reportDetailByPeriod', ['query' => $params]);
    }
}
