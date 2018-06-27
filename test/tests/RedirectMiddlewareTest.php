<?php

namespace WellRESTed\Redirect\Test;

use PHPUnit\Framework\TestCase;
use WellRESTed\Message\Response;
use WellRESTed\Message\ServerRequest;
use WellRESTed\Message\Uri;
use WellRESTed\Redirect\RedirectMiddleware;
use WellRESTed\Test\Doubles\NextDouble;

class RedirectMiddlewareTest extends TestCase
{
    /** @var int */
    private $statusCode = 302;
    /** @var string */
    private $location = '/';

    private $request;
    private $response;
    private $next;

    public function setUp()
    {
        $this->request = (new ServerRequest())
            ->withUri(new Uri('http://localhost/my/path'));
        $this->response = new Response();
        $this->next = new NextDouble();
    }

    private function dispatch()
    {
        $middleware = new RedirectMiddleware($this->statusCode, $this->location);
        return $middleware($this->request, $this->response, $this->next);
    }

    // -------------------------------------------------------------------------

    public function testSetsResponseStatusCode()
    {
        $response = $this->dispatch();
        $this->assertEquals($this->statusCode, $response->getStatusCode());
    }

    public function testSetsLocation()
    {
        $response = $this->dispatch();
        $this->assertEquals($this->location, $response->getHeaderLine('Location'));
    }
}
