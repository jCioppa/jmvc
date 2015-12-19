<?php

namespace Contracts\Exceptions; 

interface HttpExceptionInterface
{
    public function getStatusCode();
    public function getHeaders();
}
