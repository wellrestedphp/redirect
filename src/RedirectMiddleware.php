<?php

namespace WellRESTed\Redirect;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

/** @deprecated 1.1.0 We suggest using RedirectHandler */
class RedirectMiddleware
{
    private $statusCode;
    private $location;

    /** @param $statusCode */
    public function __construct($statusCode, $path)
    {
        $this->statusCode = $statusCode;
        $this->location = $path;
    }

    public function __invoke(RequestInterface $request, ResponseInterface $response, $next)
    {
        $response = $response->withStatus($this->statusCode);
        $response = $response->withHeader('Location', $this->location);
        return $next($request, $response);
    }
}
