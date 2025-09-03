<?php

namespace DmitrijKalugin\WildberriesApiClient\Exceptions;

class RateLimitException extends WildberriesApiException
{
    /**
     * @param string $message
     * @return void
     */
    public function __construct(string $message = "Rate limit exceeded")
    {
        parent::__construct($message, 429);
    }
}
