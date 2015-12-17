<?php

namespace Contracts\Http;

class Kernel {
    public function bootstrap();
    public function handle($request);
    public function terminate($request, $response);
    public function getApplication();
}
