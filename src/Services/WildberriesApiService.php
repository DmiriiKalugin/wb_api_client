<?php

namespace DmitrijKalugin\WildberriesApiClient\Services;

use DmitrijKalugin\WildberriesApiClient\Contracts\WildberriesClientInterface;

class WildberriesApiService
{
    protected WildberriesClientInterface $client;

    public function __construct(WildberriesClientInterface $client)
    {
        $this->client = $client;
    }

    /**
     * Set API token
     */
    public function setToken(string $token): self
    {
        $this->client->setToken($token);
        return $this;
    }

    /**
     * Get current API token
     */
    public function getToken(): ?string
    {
        return $this->client->getToken();
    }

    /**
     * Check API connection
     */
    public function ping(string $service = 'common'): array
    {
        return $this->client->ping($service);
    }

    /**
     * Get seller information
     */
    public function getSellerInfo(): array
    {
        return $this->client->get('/api/v1/seller-info');
    }

    /**
     * Get news from seller portal
     */
    public function getNews(array $params = []): array
    {
        return $this->client->get('/api/communications/v2/news', $params);
    }

    /**
     * Make a GET request
     */
    public function get(string $endpoint, array $params = []): array
    {
        return $this->client->get($endpoint, $params);
    }

    /**
     * Make a POST request
     */
    public function post(string $endpoint, array $data = []): array
    {
        return $this->client->post($endpoint, $data);
    }

    /**
     * Make a PUT request
     */
    public function put(string $endpoint, array $data = []): array
    {
        return $this->client->put($endpoint, $data);
    }

    /**
     * Make a DELETE request
     */
    public function delete(string $endpoint, array $data = []): array
    {
        return $this->client->delete($endpoint, $data);
    }

    /**
     * Make request to specific service
     */
    public function requestToService(string $service, string $method, string $endpoint, array $options = []): array
    {
        return $this->client->requestToService($service, $method, $endpoint, $options);
    }

    // Specific API methods based on Wildberries API documentation

    /**
     * Content API Methods
     */

    /**
     * Get product cards list
     */
    public function getCards(array $params = []): array
    {
        return $this->client->requestToService('content', 'GET', '/content/v2/get/cards/list', ['query' => $params]);
    }

    /**
     * Update product cards
     */
    public function updateCards(array $cards): array
    {
        return $this->client->requestToService('content', 'POST', '/content/v2/cards/update', ['json' => $cards]);
    }

    /**
     * Get product media files
     */
    public function getMediaFiles(int $nmId): array
    {
        return $this->client->requestToService('content', 'GET', "/content/v2/media/{$nmId}");
    }

    /**
     * Marketplace API Methods
     */

    /**
     * Get warehouses list
     */
    public function getWarehouses(): array
    {
        return $this->client->requestToService('marketplace', 'GET', '/api/v3/warehouses');
    }

    /**
     * Get orders
     */
    public function getOrders(array $params = []): array
    {
        return $this->client->requestToService('marketplace', 'GET', '/api/v3/orders', ['query' => $params]);
    }

    /**
     * Get stocks
     */
    public function getStocks(array $params = []): array
    {
        return $this->client->requestToService('marketplace', 'GET', '/api/v3/stocks', ['query' => $params]);
    }

    /**
     * Update stocks
     */
    public function updateStocks(array $stocks): array
    {
        return $this->client->requestToService('marketplace', 'PUT', '/api/v3/stocks', ['json' => ['stocks' => $stocks]]);
    }

    /**
     * Statistics API Methods
     */

    /**
     * Get income statistics
     */
    public function getIncomes(array $params = []): array
    {
        return $this->client->requestToService('statistics', 'GET', '/api/v1/supplier/incomes', ['query' => $params]);
    }

    /**
     * Get sales statistics
     */
    public function getSales(array $params = []): array
    {
        return $this->client->requestToService('statistics', 'GET', '/api/v1/supplier/sales', ['query' => $params]);
    }

    /**
     * Get stock statistics
     */
    public function getStockStatistics(array $params = []): array
    {
        return $this->client->requestToService('statistics', 'GET', '/api/v1/supplier/stocks', ['query' => $params]);
    }

    /**
     * Advertising API Methods
     */

    /**
     * Get advertising campaigns
     */
    public function getAdvertCampaigns(): array
    {
        return $this->client->requestToService('advert', 'GET', '/adv/v1/promotion/count');
    }

    /**
     * Feedbacks API Methods
     */

    /**
     * Get feedbacks
     */
    public function getFeedbacks(array $params = []): array
    {
        return $this->client->requestToService('feedbacks', 'GET', '/api/v1/feedbacks', ['query' => $params]);
    }

    /**
     * Finance API Methods
     */

    /**
     * Get finance reports
     */
    public function getFinanceReports(array $params = []): array
    {
        return $this->client->requestToService('finance', 'GET', '/api/v1/supplier/reportDetailByPeriod', ['query' => $params]);
    }
}
