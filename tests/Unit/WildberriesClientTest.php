<?php

namespace DmitrijKalugin\WildberriesApiClient\Tests\Unit;

use DmitrijKalugin\WildberriesApiClient\Tests\TestCase;
use DmitrijKalugin\WildberriesApiClient\Http\WildberriesClient;
use DmitrijKalugin\WildberriesApiClient\Exceptions\AuthenticationException;
use DmitrijKalugin\WildberriesApiClient\Services\WildberriesApiService;
use DmitrijKalugin\WildberriesApiClient\Contracts\WildberriesClientInterface;

class WildberriesClientTest extends TestCase
{
    public function test_client_can_be_instantiated(): void
    {
        $client = new WildberriesClient();
        
        $this->assertInstanceOf(WildberriesClient::class, $client);
    }

    public function test_token_can_be_set_and_retrieved(): void
    {
        $client = new WildberriesClient();
        $token = 'test_token_123';
        
        $client->setToken($token);
        
        $this->assertEquals($token, $client->getToken());
    }

    public function test_base_url_can_be_retrieved(): void
    {
        $client = new WildberriesClient();
        
        $baseUrl = $client->getBaseUrl('common');
        
        $this->assertStringContainsString('common-api.wildberries.ru', $baseUrl);
    }

    public function test_base_urls_can_be_updated(): void
    {
        $client = new WildberriesClient();
        
        $newUrls = [
            'common' => 'https://test-api.example.com',
        ];
        
        $client->setBaseUrls($newUrls);
        
        $this->assertEquals('https://test-api.example.com', $client->getBaseUrl('common'));
    }

    public function test_request_without_token_throws_exception(): void
    {
        $this->expectException(AuthenticationException::class);

        $client = new WildberriesClient();
        $client->get('/test');
    }

    public function test_create_cards_delegates_to_client(): void
    {
        $testCards = [
            [
                'subjectID' => 105,
                'variants' => [
                    [
                        'vendorCode' => 'ART-001',
                        'brand' => 'TestBrand',
                        'title' => 'Test Product',
                    ],
                ],
            ],
        ];

        $mockClient = $this->createMock(WildberriesClientInterface::class);
        $mockClient->expects($this->once())
            ->method('requestToService')
            ->with(
                'content',
                'POST',
                '/content/v2/cards/upload',
                ['json' => $testCards]
            )
            ->willReturn(['status' => 'ok']);

        $service = new WildberriesApiService($mockClient);
        $result = $service->createCards($testCards);

        $this->assertEquals(['status' => 'ok'], $result);
    }
}
