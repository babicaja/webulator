# Webulator

Simple web app framework utilizing PSR7 web standards and some of the top shelf vendor packages. The framework was heavily inspired by Laravel, so if you end up needing more complex functionality make sure the check it out.

## Installation

All you need to do is to clone the repository, install the dependencies and fire up a web server and you are good to go. Of course you'll need to have php, npm, yarn and composer prior to this.

```bash
git clone git@bitbucket.org:kucasoft/webulator.git
composer install
yarn install
php -S localhost:8000 -t public/
```  

## Quick Start

You would wan't to follow the next flow for introducing new functionality:

Create a new controller that extends the BaseController class .This exposes the components described bellow to your controller.

> app/Webulator/HTTP/Controllers/ExampleController.php

```php
<?php

namespace Webulator\HTTP\Controllers;

class ExampleController extends BaseController
{

}
```

Add an action method, do your stuff and return the response object. Remember, the response property is provided by the BaseController.

```php
<?php

namespace Webulator\HTTP\Controllers;

class ExampleController extends BaseController
{
    public function index()
    {
        $this->response->getBody()->write("Hello World");
        
        return $this->response;
    }
}
``` 

Create a route for your new functionality.

> public/index.php

```php
<?php

require __DIR__ . '/../vendor/autoload.php';

try
{
    // Boot the app.
    $app = require __DIR__ . '/../bootstrap/app.php';

    // Define routes for the application.
    $app->routes()->get("/", "HomeController@welcome");
    $app->routes()->get("/example", "ExampleController@index");
    
    // Execute.
    $app->run();
}
catch (Throwable $throwable)
{
    \Webulator\ExceptionHandler::capture($throwable);
}
```

Visit the page in your browser

> http://localhost:8000/example

![Browser](http://i64.tinypic.com/99i838.png)

## Components
## Assets
## Unit Tests
## Feature Tests