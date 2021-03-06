# Front Controller

The Front Controller is responsible to establish the **Channel Independency Border**,
which hides the outer world from Joomla! components.

Currently three different channels are distinguished on input side:

  - **Web** *(browser)*. This is the normal access to a resource as a web page via HTTP(S).
    All input values are found in `$_REQUEST`, but may be encoded in the URL as well..
    Routing information usually is encoded in the URL `$_SERVER['REQUEST_URI']`.
    Methods are restricted to `GET` and `POST`.
    The entry script is `index.php`.

  - **API** *(WebAPI)*. This access method supports all HTTP verbs.
    All input values are found in `$_REQUEST`, but may be encoded in the URL as well..
    Routing is determined by the HTTP verb `$_SERVER['REQUEST_METHOD']` or `$_SERVER['REQUEST_METHOD']`
    and the URL `$_SERVER['REQUEST_URI']`.
    The entry script is `api.php`.

  - **CLI** *(command line)*.
    The input values and any routing information are provided through `$_SERVER['argv']`.
    The entry script is `joomla.php`, which usually gets symlinked into the binary path as `joomla`.

On the output side, the output can be turned into streams sufficient for different environments.
The transformation is done by a `Renderer` instance. Available renderers are

  - **HTML.** This is the usual format for web pages.
    It comes in different flavors, f.x. `LegacyHtmlRenderer` for maximum compatibility with current 3.x templates
    and a modern variant `HtmlRenderer` with the most recent BootStrap version.
    Other HTML renderers can be added later, f.x. to support AngularJS.
    
  - **CSV.** This output format is readable by spreadsheet applications.
   
  - **Plain text.** When Joomla! is used on the console, usually plain text output is wanted.
   
  - **JSON, XML.** These renderers produce structured output as needed for web services.
   
Other output formats like PDF, ASCIIDoc, or ePub can be provided with suitable `Renderer` implementations.

## Support of PSR-7 HTTP message interfaces

Joomla!4 implements PSR-7 support. Although the `ServerRequestInterface` originally is designed for HTTP only,
it is used for CLI access as well.

### Mapping HTTP properties to CLI properties

  - `ProtocolVersion`: CLI version, currently always '1.0'.
  - `Headers`: Fake headers, e.g., for `Accept:` ('text/plain' or 'text/ansi').
  - `Body`: Usually empty. May be used to transport data from files (e.g., import files)
  - `RequestTarget`: The script name, usually 'joomla.php', followed by the sub-command.
  - `Method`: Always 'CLI'.
  - `Uri`: A `UriInterface` instance for 'cli://&lt;user>@&lt;working-dir>?&lt;commandline-arguments>'.
  - `ServerParams`: Data related to the incoming request environment, typically derived from `$_SERVER`.
  - `CookieParams`: Environment variables, accessed through `getenv()`/`putenv()` (*not* `$_ENV`).
  - `QueryParams`: The command line arguments and options.
  - `UploadedFiles`: Uploaded files as in an HTTP request.
  - `ParsedBody`: Parsed data from `Body`.
  - `Attributes`: Any attribute derived from the request data.

**External Dependencies:**

  - [psr/http-message](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-7-http-message.md)
  - [zend/diactoros](https://github.com/zendframework/zend-diactoros)

## Routing

> This is channel dependent. Output is a `ServerRequestInterface` instance.

The job of the `Router` is to determine, which command has to be issued. It abstracts the input environment.

The `Router` takes one argument, `$request`, which is an instance of the PSR-7 `ServerRequestInterface`.
It supports different routing strategies, depending on environment and configuration.
For the command line environment, the `CliRoutingStrategy` is used, for the WebAPI the `ApiRoutingStrategy`.
When communicating with a browser, multiple strategies are chained to satisfy different needs.
These are

  - `SeoRoutingStrategy` manages individual search engine friendly URLs.
  - `LegacyRoutingStrategy` is a wrapper to the routing used in Joomla! before version 4.
  - `DefaultRoutingStrategy` is the standard routing in Joomla!4.
  
Unused strategies can be removed and other strategies can be added.

```php
// CLI environment, in joomla.php
$router = new Router([
    new CliRoutingStrategy
]);

// WebAPI environment, in api.php
$router = new Router([
    new ApiRoutingStrategy
]);

// Browser environment, in index.php
$router = new Router([
    new SeoRoutingStrategy,
    new LegacyRoutingStrategy,
    new DefaultRoutingStrategy
]);

$dispatcher->trigger('onRouterSetup', $router);

$request = (new ServerRequestFactory)->createFromGlobals();
$request = $router->route($request);
```

The `Router` calls the routing strategy chain, returning a modified `ServerRequestInterface` instance.

## Renderer Creation

The `Renderer` is created dependent on the `Accept:` header of the request.

```php
$renderer = (new RendererFactory)->create($request->getHeaderLine('accept'));
```

## Command Creation

> This is channel independent. Input is a `ServerRequestInterface` instance. Output is a `Command` instance.

Next, the `Command` is located. To keep that flexible, strategies are used here as well.
Depending on the values in `$request`, the strategies try to locate a corresponding command.
The `DatabaseLocatorStrategy` looks into the `extensions` table in the database to get the namespace for the component,
and attempts to instantiate the command according to the naming convention.

For extensions without a namespace, i.e., legacy code, 
the `LegacyMvcLocatorStrategy` returns a command, that just calls the component's bootstrap file,
relying on that it will act correctly according to the input.

Other strategies can be added.

```php
$locator = new CommandLocator([
    new DatabaseLocatorStrategy,
    new LegacyMvcLocatorStrategy
]);

$dispatcher->trigger('onCommandLocatorSetup', $locator);

$command = $locator->find($request);
```

## Command Execution

> This is channel independent. Input is a `Command` instance. Output is a `Content` item (tree).

The `Command` is handled by a `CommandBus`, which supports nested middleware.
Each Middleware is a separate object and can do anything it wants.

```php
$commandBus = new CommandBus([
    new LoggingMiddleware($logger),
    new EventMiddleware($emitter),
    new CommandHandlerMiddleware
]);

$dispatcher->trigger('onCommandBusSetup', $commandBus);

$content = $commandBus->handle($command)

if (!$content instanceof 'Content')
{
    $content = new LegacyContentItem($content);
}
```

> **Note:** Maybe parts of the command location process should go into the `CommandHandlerMiddleware`.

**External Dependencies:**

  - [league/tactician](http://tactician.thephpleague.com/)
  
## Rendering

> This is channel dependent. Input is a `Content` item (tree). Output is a `Response` object.

```php
$renderer = (new RendererFactory)->create($request->getHeaderLine('accept'));
$content->accept($renderer);
$response = new Response($renderer->getContent(), $renderer->getStatus(), $renderer->getHeaders());
```

**External Dependencies:**

  - [psr/http-message](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-7-http-message.md)

## Compiled Code

```php
function prepareInput()
{
    // CLI environment, in joomla.php
    $router = new Router([
        new CliRoutingStrategy
    ]);
    
    // WebAPI environment, in api.php
    $router = new Router([
        new ApiRoutingStrategy
    ]);
    
    // Browser environment, in index.php
    $router = new Router([
        new SeoRoutingStrategy,
        new LegacyRoutingStrategy,
        new DefaultRoutingStrategy
    ]);
    
    $dispatcher->trigger('onRouterSetup', $router);
    
    $request = $router->route((new ServerRequestFactory)->createFromGlobals());
}

function prepareOutput($request)
{
    $renderer = (new RendererFactory)->create($request->getHeaderLine('accept'));
    $response = new Response($renderer);
}

function handle($input, $output)
{
    $locator  = new CommandLocator([
        new DatabaseLocatorStrategy,
        new LegacyMvcLocatorStrategy
    ]);
    
    $dispatcher->trigger('onCommandLocatorSetup', $locator);
    
    $command = $locator->find($input);
    
    $commandBus = new CommandBus([
        new LoggingMiddleware($logger),
        new EventMiddleware($emitter),
        new CommandHandlerMiddleware
    ]);
    
    $dispatcher->trigger('onCommandBusSetup', $commandBus);
    
    $commandBus->handle($command, $output)
}

$input  = prepareInput();
$output = prepareOutput($input);
handle($input, $output);
$output->emit();
```
