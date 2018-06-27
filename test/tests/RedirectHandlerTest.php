<?php

namespace WellRESTed\Redirect\Test;

use Psr\Http\Server\RequestHandlerInterface;
use WellRESTed\Message\Response;
use WellRESTed\Message\ServerRequest;
use WellRESTed\Message\Uri;
use WellRESTed\Redirect\RedirectHandler;
use WellRESTed\Test\TestCases\RequestHandlerTestCase;

class RedirectHandlerTest extends RequestHandlerTestCase
{
    /** @var int */
    private $statusCode = 302;
    /** @var string */
    private $location = '/';

    public function setUp()
    {
        parent::setUp();
        $this->request = (new ServerRequest())
            ->withUri(new Uri('http://localhost/my/path'));
    }

    protected function getHandler(): RequestHandlerInterface
    {
        return new RedirectHandler(
            $this->statusCode,
            $this->location,
            new Response()
        );
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
