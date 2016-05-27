# WellRESTed Redirect

PSR-7 middleware for simple redirects

Same usage:

```php
<?php

use WellRESTed\Redirect\RedirectMiddleware;
use WellRESTed\Server;

$server = new Server();
$server->add($server->createRouter()
    ->register('GET', '/old-path', new RedirectMiddleware(301, '/new-path'))
);
$server->respond();
```

When the middleware is dispatched, it will return a response with the provided status code and location.

When using a dependency container, you may want to wrap `RedirectMiddleware` with a tiny factory.

```php
<?php

use Pimple\Container;
use Pimple\ServiceProviderInterface;
use WellRESTed\Redirect\RedirectMiddleware;
use WellRESTed\Server;

class ServiceProvider implements ServiceProviderInterface
{
    public function register(Container $c)
    {
        $c['redirect'] = $c->protect(
            function ($status, $location) {
                return new RedirectMiddleware($status, $location);
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
