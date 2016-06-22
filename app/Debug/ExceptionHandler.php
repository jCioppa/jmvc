<?php

namespace App\Debug;

class ExceptionHandler implements \Contracts\Debug\ExceptionHandlerInterface
{

    // list of exception types to not report
    protected $dontReport = [];

    public function report(\Exception $e)
    {
        if ($this->shouldReport($e)) {
           // app('Psr\Log\LoggerInterface')->error($e);
        }
    }

    public function shouldReport(\Exception $e)
    {
        return ! $this->shouldntReport($e);
    }

    protected function shouldntReport(\Exception $e)
    {
        foreach ($this->dontReport as $type) {
            if ($e instanceof $type) {
                return true;
            }
        }
        return false;
    }

    public function render($request, \Exception $e)
    {
        return $this->toResponse($request, $e);
    }

    protected function toResponse($request, \Exception $e)
    {
        $response = new \App\Http\Response\ExceptionResponse ($e, $e->getMessage(), $e->getStatusCode(),[]);
        $err = error($e->getStatusCode(), $e->getMessage());
        $response->setContent(error($e->getStatusCode(), $e->getMessage()));
        return $response;
    }

}
