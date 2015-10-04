# Front Controller

The Front Controller is responsible to establish the **Channel Independency Border**,
which hides the outer world from Joomla! components.

We currently distinguish three different channels:

  - **Browser.** This is the normal access to a resource as a web page via HTTP(S).
    All input values are found in `$_REQUEST`, but may be encoded in the URL as well..
    Routing information usually is encoded in the URL `$_SERVER['REQUEST_URI']`.
    Methods are restricted to `GET` and `POST`.
    Any renderer can be used, most common are HTML, PDF, or CSV.
    The entry script is `index.php`.

  - **WebAPI.** This access method supports all HTTP verbs.
    All input values are found in `$_REQUEST`, but may be encoded in the URL as well..
    Routing is determined by the HTTP verb `$_SERVER['REQUEST_METHOD']` or `$_POST['REQUEST_METHOD']`
    and the URL `$_SERVER['REQUEST_URI']`.
    Any renderer can be used, most common are XML or JSON.
    The entry script is `api.php`.

  - **Command Line (CLI).**
    The input values and any routing information are provided through `$_SERVER['argv']`.
    A plain text or ANSI renderer (`--ansi`) is used.
    The entry script is `joomla.php`, which usually gets symlinked into the binary path as `joomla`.
    
## Routing

The job of the `Router` is to determine, which command has to be issued.

The `Router` takes two arguments, `$request` and `$server`, which are copies of `$_REQUEST` and `$_SERVER`, respectively.
It supports different routing strategies, depending on environment and configuration.
For the command line environment, the `CliRouting` strategy is used, for the WebAPI the `ApiRouting` strategy.
When communicating with a browser, multiple strategies are chained to satisfy different needs.
These are

  - `SeoRouting` manages individual search engine friendly URLs.
  - `LegacyRouting` is a wrapper to the routing used in Joomla! before version 4.
  - `DefaultRouting` is the standard routing in Joomla!4.
  
Unused strategies can be removed, other strategies can be added in the configuration.

```php
$server = $_SERVER;
$input  = $_REQUEST;

// CLI environment
$router = new Router([
    new CliRouting,
    new CommandNotFoundError
]);

// WebAPI environment
$router = new Router([
    new ApiRouting,
    new ResourceNotFoundError
]);

// Browser environment
$router = new Router([
    new SeoRouting,
    new LegacyRouting,
    new DefaultRouting,
    new ResourceNotFoundError
]);

$command = $router->route($input, $server);
```

The `Router` modifies the `$input` according to the values found in `$input` and `$server`.
It calls one routing strategy after the other, until one strategy returns a `Command` object.
The last strategy in each chain is a `CommandNotFoundError` or `ResourceNotFoundError`,
which returns a `Command` creating an appropriate error reaction.
