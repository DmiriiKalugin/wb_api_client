<?php

namespace DmitrijKalugin\WildberriesApiClient\Exceptions;

class AuthenticationException extends WildberriesApiException
{
    public function __construct(string $message = "Authentication failed")
    {
        parent::__construct($message, 401);
    }
}
