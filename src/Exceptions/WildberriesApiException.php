<?php

namespace DmitrijKalugin\WildberriesApiClient\Exceptions;

use Exception;

class WildberriesApiException extends Exception
{
    protected array $apiErrors = [];

    /**
     * @param string $message
     * @param int $code
     * @param Exception|null $previous
     * @param array $apiErrors
     */
    public function __construct(
        string $message = "",
        int $code = 0,
        ?Exception $previous = null,
        array $apiErrors = []
    ) {
        parent::__construct($message, $code, $previous);
        $this->apiErrors = $apiErrors;
    }

    /**
     * @return array
     */
    public function getApiErrors(): array
    {
        return $this->apiErrors;
    }

    /**
     * @param array $response
     * @param int $statusCode
     * @return self
     */
    public static function fromResponse(array $response, int $statusCode): self
    {
        $message = $response['message'] ?? 'Unknown API error';
        $errors = $response['errors'] ?? [];
        
        return new self($message, $statusCode, null, $errors);
    }
}
