<?php

namespace DmitrijKalugin\WildberriesApiClient\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static array ping(string $service = 'common')
 * @method static array getSellerInfo()
 * @method static array getNews(array $params = [])
 * @method static array get(string $endpoint, array $params = [])
 * @method static array post(string $endpoint, array $data = [])
 * @method static array put(string $endpoint, array $data = [])
 * @method static array delete(string $endpoint, array $data = [])
 * @method static \DmitrijKalugin\WildberriesApiClient\Services\WildberriesApiService setToken(string $token)
 * @method static string|null getToken()
 * 
 * @see \DmitrijKalugin\WildberriesApiClient\Services\WildberriesApiService
 */
class Wildberries extends Facade
{
    /**
     * Get the registered name of the component.
     */
    protected static function getFacadeAccessor(): string
    {
        return 'wildberries';
    }
}
