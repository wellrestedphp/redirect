# WellRESTed Redirect

[PSR-15](https://www.php-fig.org/psr/psr-15/) handler for simple redirects

Same usage:

```php
<?php

use WellRESTed\Redirect\RedirectHandler;
use WellRESTed\Message\Response;
use WellRESTed\Server;

$server = new Server();
$server->add($server->createRouter()
    ->register('GET', '/old-path', new RedirectHandler(301, '/new-path', new Response()))
);
$server->respond();
```

When the handler is dispatched, it will return a response with the provided status code and location.

When using a dependency container, you may want to wrap `RedirectHandler` with a tiny factory.

```php
<?php

use Pimple\Container;
use Pimple\ServiceProviderInterface;
use WellRESTed\Redirect\RedirectHandler;
use WellRESTed\Server;

class ServiceProvider implements ServiceProviderInterface
{
    public function register(Container $c)
    {
        $c['redirect'] = $c->protect(
            function ($status, $location) {
                return new RedirectHandler($status, $location, new Response());
            }
        );

        $c['server'] = function ($c) {
            $server = new Server();
            $server->add($c['app:notFoundHandler']);
            $server->add($server->createRouter()
                ->register('GET', '/', [
                    $c['app:rootHandler']
                ])
                ->register('GET',  '/old-path', [
                    $c['redirect'](301, '/new-path')
                ])
                ->register('GET',  '/new-path', [
                    $$c['app:newThing']
                ])
                ->register('POST', '/login', [
                    $c['app:loginHandler'],
                    $c['redirect'](303, '/')
                ])
            );
            return $server;
        };
    }
}
```
