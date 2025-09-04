<?php

namespace DmitrijKalugin\WildberriesApiClient\Contracts;

interface WildberriesClientInterface
{
    /**
     * @param string $token
     * @return self
     */
    public function setToken(string $token): self;

    /**
     * @return string|null
     */
    public function getToken(): ?string;

    /**
     * @param string $endpoint
     * @param array $params
     * @return array
     */
    public function get(string $endpoint, array $params = []): array;

    /**
     * @param string $endpoint
     * @param array $data
     * @return array
     */
    public function post(string $endpoint, array $data = []): array;

    /**
     * @param string $endpoint
     * @param array $data
     * @return array
     */
    public function put(string $endpoint, array $data = []): array;

    /**
     * @param string $endpoint
     * @param array $data
     * @return array
     */
    public function delete(string $endpoint, array $data = []): array;

    /**
     * @param string $service
     * @return array
     */
    public function ping(string $service = 'common'): array;

    /**
     * @param string $service
     * @param string $method
     * @param string $endpoint
     * @param array $options
     * @return array
     */
    public function requestToService(string $service, string $method, string $endpoint, array $options = []): array;
}
