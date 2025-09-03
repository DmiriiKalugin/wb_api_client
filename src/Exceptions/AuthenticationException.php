<?php

namespace DmitrijKalugin\WildberriesApiClient\Exceptions;

class AuthenticationException extends WildberriesApiException
{
    /**
     * @param string $message
     * @return void
     */
    public function __construct(string $message = "Authentication failed")
    {
        parent::__construct($message, 401);
    }
}
