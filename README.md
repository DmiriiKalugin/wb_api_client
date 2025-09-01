# Wildberries API Client для Laravel

Laravel пакет для интеграции с API Wildberries. Обеспечивает удобный доступ ко всем основным API сервисам маркетплейса.

## Особенности

- ✅ Поддержка всех основных API Wildberries (Content, Marketplace, Statistics, Advertising и др.)
- ✅ Автоматическая авторизация через API токены
- ✅ Обработка ошибок и лимитов запросов
- ✅ Поддержка песочницы (sandbox)
- ✅ Laravel Service Provider и Facade
- ✅ Гибкая конфигурация
- ✅ PSR-4 автозагрузка

## Установка

Установите пакет через Composer:

```bash
composer require dmitrijkalugin/wildberries-api-client
```

Опубликуйте конфигурационный файл:

```bash
php artisan vendor:publish --tag=wildberries-config
```

## Конфигурация

Добавьте ваш API токен в `.env` файл:

```env
WILDBERRIES_API_TOKEN=your_api_token_here
WILDBERRIES_SANDBOX=false
WILDBERRIES_LOGGING=false
```

Получить API токен можно в личном кабинете продавца в разделе "Настройки" → "Доступ к API".

## Быстрый старт

### Использование через Facade

```php
use DmitrijKalugin\WildberriesApiClient\Facades\Wildberries;

// Проверка соединения
$ping = Wildberries::ping();

// Получение информации о продавце
$sellerInfo = Wildberries::getSellerInfo();

// Получение новостей
$news = Wildberries::getNews(['from' => '2024-01-01']);

// Получение товаров
$cards = Wildberries::getCards(['limit' => 100]);
```

### Использование через Dependency Injection

```php
use DmitrijKalugin\WildberriesApiClient\Services\WildberriesApiService;

class YourController extends Controller
{
    public function __construct(
        private WildberriesApiService $wildberriesApi
    ) {}

    public function getProducts()
    {
        $products = $this->wildberriesApi->getCards();
        return response()->json($products);
    }
}
```

### Динамическая смена токена

```php
Wildberries::setToken('your_new_token')->getSellerInfo();
```

## Доступные методы

### Общие методы

```php
// Проверка соединения с различными сервисами
Wildberries::ping('common');     // common-api
Wildberries::ping('content');    // content-api
Wildberries::ping('marketplace'); // marketplace-api

// Информация о продавце
Wildberries::getSellerInfo();

// Новости портала продавцов
Wildberries::getNews([
    'from' => '2024-01-01',        // Дата от
    'fromID' => 7369               // ID новости от
]);
```

### Content API - Управление товарами

```php
// Получение списка карточек товаров
$cards = Wildberries::getCards([
    'limit' => 100,
    'offset' => 0
]);

// Обновление карточек товаров
$updateResult = Wildberries::updateCards([
    [
        'nmID' => 123456,
        'title' => 'Новое название товара',
        'description' => 'Описание товара'
    ]
]);

// Получение медиафайлов товара
$media = Wildberries::getMediaFiles(123456);
```

### Marketplace API - Заказы и остатки

```php
// Получение списка складов
$warehouses = Wildberries::getWarehouses();

// Получение заказов
$orders = Wildberries::getOrders([
    'dateFrom' => '2024-01-01',
    'dateTo' => '2024-01-31'
]);

// Получение остатков
$stocks = Wildberries::getStocks([
    'dateFrom' => '2024-01-01'
]);

// Обновление остатков
$stocksUpdate = Wildberries::updateStocks([
    [
        'sku' => 'SKU123',
        'amount' => 10,
        'warehouseId' => 123
    ]
]);
```

### Statistics API - Аналитика

```php
// Статистика поставок
$incomes = Wildberries::getIncomes([
    'dateFrom' => '2024-01-01'
]);

// Статистика продаж
$sales = Wildberries::getSales([
    'dateFrom' => '2024-01-01',
    'flag' => 0
]);

// Остатки товаров
$stockStats = Wildberries::getStockStatistics([
    'dateFrom' => '2024-01-01'
]);
```

### Advertising API - Реклама

```php
// Получение рекламных кампаний
$campaigns = Wildberries::getAdvertCampaigns();
```

### Feedbacks API - Отзывы

```php
// Получение отзывов
$feedbacks = Wildberries::getFeedbacks([
    'isAnswered' => false,
    'take' => 100,
    'skip' => 0
]);
```

### Finance API - Финансы

```php
// Получение финансовых отчетов
$reports = Wildberries::getFinanceReports([
    'dateFrom' => '2024-01-01',
    'dateTo' => '2024-01-31'
]);
```

## Прямые HTTP запросы

Если нужного метода нет в библиотеке, можно делать прямые HTTP запросы:

```php
// GET запрос
$response = Wildberries::get('/api/endpoint', ['param' => 'value']);

// POST запрос
$response = Wildberries::post('/api/endpoint', ['data' => 'value']);

// Запрос к конкретному сервису
$response = Wildberries::requestToService(
    'content',           // сервис
    'GET',              // метод
    '/content/v2/cards', // endpoint
    ['query' => ['limit' => 100]]
);
```

## Обработка ошибок

Библиотека автоматически обрабатывает HTTP ошибки и выбрасывает соответствующие исключения:

```php
use DmitrijKalugin\WildberriesApiClient\Exceptions\AuthenticationException;
use DmitrijKalugin\WildberriesApiClient\Exceptions\RateLimitException;
use DmitrijKalugin\WildberriesApiClient\Exceptions\WildberriesApiException;

try {
    $result = Wildberries::getSellerInfo();
} catch (AuthenticationException $e) {
    // Ошибка авторизации (401)
    logger()->error('WB Auth Error: ' . $e->getMessage());
} catch (RateLimitException $e) {
    // Превышен лимит запросов (429)
    logger()->warning('WB Rate Limit: ' . $e->getMessage());
} catch (WildberriesApiException $e) {
    // Другие API ошибки
    logger()->error('WB API Error: ' . $e->getMessage());
    $apiErrors = $e->getApiErrors(); // Дополнительная информация об ошибках
}
```

## Конфигурация

Полная конфигурация в `config/wildberries.php`:

```php
return [
    // API токен
    'token' => env('WILDBERRIES_API_TOKEN'),
    
    // Режим песочницы
    'sandbox' => env('WILDBERRIES_SANDBOX', false),
    
    // HTTP настройки
    'http' => [
        'timeout' => 30,
        'retry_attempts' => 3,
        'retry_delay' => 1000,
    ],
    
    // Ограничения запросов
    'rate_limiting' => [
        'enabled' => true,
        'default_limit' => 60,
        'burst_limit' => 10,
    ],
    
    // Логирование
    'logging' => [
        'enabled' => env('WILDBERRIES_LOGGING', false),
        'channel' => env('WILDBERRIES_LOG_CHANNEL', 'default'),
        'level' => env('WILDBERRIES_LOG_LEVEL', 'info'),
    ],
];
```

## Лимиты API

Wildberries устанавливает лимиты на количество запросов:

- **Общие методы**: 1 запрос в минуту, всплеск до 10 запросов
- **Новости**: 1 запрос в минуту, всплеск до 10 запросов
- **Другие методы**: согласно документации API

Библиотека автоматически обрабатывает ошибки превышения лимитов (429 статус код).

## Тестирование

Для тестирования используйте sandbox режим:

```env
WILDBERRIES_SANDBOX=true
```

В этом режиме запросы будут направляться на тестовые URL:
- `https://common-api-sandbox.wildberries.ru`
- `https://content-api-sandbox.wildberries.ru`
- и т.д.

## Примеры использования

### Автоматическое обновление остатков

```php
use DmitrijKalugin\WildberriesApiClient\Facades\Wildberries;

class StockUpdater
{
    public function updateStocks(array $products)
    {
        $stocks = [];
        
        foreach ($products as $product) {
            $stocks[] = [
                'sku' => $product['sku'],
                'amount' => $product['quantity'],
                'warehouseId' => $product['warehouse_id']
            ];
        }
        
        try {
            $result = Wildberries::updateStocks($stocks);
            logger()->info('Stocks updated successfully', $result);
        } catch (\Exception $e) {
            logger()->error('Failed to update stocks: ' . $e->getMessage());
            throw $e;
        }
    }
}
```

### Синхронизация заказов

```php
class OrderSynchronizer
{
    public function syncOrders(string $dateFrom, string $dateTo)
    {
        try {
            $orders = Wildberries::getOrders([
                'dateFrom' => $dateFrom,
                'dateTo' => $dateTo
            ]);
            
            foreach ($orders as $order) {
                // Сохранение заказа в локальную БД
                $this->saveOrderToDatabase($order);
            }
            
        } catch (\Exception $e) {
            logger()->error('Order sync failed: ' . $e->getMessage());
            throw $e;
        }
    }
}
```

## Требования

- PHP 8.1 или выше
- Laravel 10.0 или выше
- GuzzleHTTP 7.0 или выше

## Лицензия

MIT

## Поддержка

При возникновении проблем:

1. Проверьте корректность API токена
2. Убедитесь, что не превышены лимиты запросов
3. Проверьте документацию Wildberries API
4. Создайте issue в репозитории

## Полезные ссылки

- [Документация Wildberries API](https://dev.wildberries.ru/openapi/api-information)
- [Личный кабинет продавца](https://seller.wildberries.ru/)
- [Telegram канал с новостями API](https://t.me/wbapi)
