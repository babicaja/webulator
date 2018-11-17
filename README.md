# Webulator

Simple web app framework utilizing PSR7 web standards and some of the top shelf vendor packages. The framework was heavily inspired by Laravel, so if you end up needing more complex functionality make sure the check it out.

## Installation

All you need to do is to clone the repository, install the dependencies and fire up a web server and you are good to go. Of course you'll need to have php, npm, yarn and composer prior to this.

```bash
git clone git@bitbucket.org:kucasoft/webulator.git
composer install
yarn install
npm run dev
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

Visit the page in your browser.

> http://localhost:8000/example

![Browser](http://i64.tinypic.com/99i838.png)

## Components

The components you are most likely to use in your controller are listed bellow.

| Component     | Based on                     | Accessed by            |
|---------------|------------------------------|------------------------|
| Request       | zendframework/zend-diactoros | `$this->request`       |
| Response      | zendframework/zend-diactoros | `$this->response`      |
| Configuration | hassankhan/config            | `$this->configuration` |
| Template      | twig/twig                    | `$this->template`      |
| Logger        | monolog/monolog              | `$this->logger`        |
| Database      | usmanhalalit/pixie           | `$this->database`      |
| HTTPClient    | guzzlehttp/guzzle            | `$this->HTTPClient`    |

## Assets

Assets utilize the power of Webpack. You are provided with a basic but yet powerful `webpack.config.js` which should cover most of your needs for assets compilation. The configuration provides:

- Module compilation
- Babel transpilling
- Sass compilation
- Image optimization
- Asset cleanup

Entry point for javascript is `assets/js/main.js` which provides a shell for a Webulator app, but also pulls in Bootstrap and jQuery.

CSS is compiled through Sass starting with `assets/css/main.scss` and this provides a bootswatch theme.

You can compile the assets with the `npm run dev` or if you are ready for production go with `npm run prod`

