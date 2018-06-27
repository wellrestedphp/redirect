<?php

namespace WellRESTed\Redirect\Test;

use PHPUnit\Framework\TestCase;
use WellRESTed\Message\Request;
use WellRESTed\Message\Response;
use WellRESTed\Message\Uri;
use WellRESTed\Redirect\RedirectMiddleware;

class RedirectMiddlewareTest extends TestCase
{
    private $request;
    private $response;
    private $next;

    public function setUp()
    {
        $this->request = (new Request())
            ->withUri(new Uri('http://localhost/my/path'));
        $this->response = new Response();
        $this->next = function ($rqst, $resp) { return $resp; };
    }

    private function dispatch($middleware)
    {
        return $middleware($this->request, $this->response, $this->next);
    }

    // -------------------------------------------------------------------------

    public function testSetsResponseStatusCode()
    {
        $statusCode = 302;
        $middleware = new RedirectMiddleware($statusCode, '/');
        $response = $this->dispatch($middleware);
        $this->assertEquals($statusCode, $response->getStatusCode());
    }

    public function testSetsLocation()
    {
        $statusCode = 302;
        $middleware = new RedirectMiddleware($statusCode, '/new/path');
        $response = $this->dispatch($middleware);
        $this->assertEquals('/new/path', $response->getHeaderLine('Location'));
    }
}
