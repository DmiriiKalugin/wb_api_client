<?php

namespace DmitrijKalugin\WildberriesApiClient\Contracts;

interface WildberriesClientInterface
{
    /**
     * Set API token for authentication
     */
    public function setToken(string $token): self;

    /**
     * Get current API token
     */
    public function getToken(): ?string;

    /**
     * Make GET request to API
     */
    public function get(string $endpoint, array $params = []): array;

    /**
     * Make POST request to API
     */
    public function post(string $endpoint, array $data = []): array;

    /**
     * Make PUT request to API
     */
    public function put(string $endpoint, array $data = []): array;

    /**
     * Make DELETE request to API
     */
    public function delete(string $endpoint, array $data = []): array;

    /**
     * Check API connection
     */
    public function ping(string $service = 'common'): array;
}
