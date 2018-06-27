<?php

namespace WellRESTed\Redirect;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class RedirectHandler implements RequestHandlerInterface
{
    /** @var int */
    private $statusCode;
    /** @var string */
    private $location;
    /** @var ResponseInterface */
    private $response;

    public function __construct(
        int $statusCode,
        string $location,
        ResponseInterface $response
    ) {
        $this->statusCode = $statusCode;
        $this->location = $location;
        $this->response = $response;
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        return $this->response
            ->withStatus($this->statusCode)
            ->withHeader('Location', $this->location);
    }
}
