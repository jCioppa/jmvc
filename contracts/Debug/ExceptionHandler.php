<?php

namespace Contracts\Debug;

interface ExceptionHandlerInterface
{
    public function report(\Exception $e);
    public function render($request, \Exception $e);
}
