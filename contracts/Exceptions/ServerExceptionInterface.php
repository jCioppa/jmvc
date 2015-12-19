<?php

namespace Contracts\Exceptions; 

interface ServerExceptionInterface
{
    public function getStatusCode();
    public function getHeaders();
}
