<?php

    namespace Contracts\HttpKernel;
    use App\Http\Request\Request;

    interface HttpKernelInterface {
        public function dispatch(Request $request);
    }
