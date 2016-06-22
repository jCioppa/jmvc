<?php

    namespace App\Http\Response;

    class ExceptionResponse extends Response {

        protected $exception;

        public function __construct($exception, $content, $statusCode, $headers = []) {
            $this->exception = $exception;
            $this->content = $content;
            $this->statusCode = $statusCode;
            $this->headers = $headers;
        } 

        public function setException($e) {
            $this->exception = $e;
        }

        public function exception() {
            return $this->exception;
        }



    }
