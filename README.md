# Slim Controller for the Slim Framework V2

A controller extension for the Slim Framework V2. 

About
------------

A quick way to define your controller routes and functions. Placing them in classes opposed to anonymous functions
using a lightweight extensible class for your controllers.

Usage
------------
```php
# Instantiate your Slim application.
$app = new \Slim\Slim();

# If you're using a namespace, include it here.
$config = [
    'namespace' => 'My\\Controller\\Namespace'
];

# Include the SlimController class in your bootstrap file.
$controller = new \Troward\SlimController\SlimController($app, $config);

# Define your routes, you can use GET, POST, PUT and DELETE.
$routes = [
    'GET' => [
        # You need to define the URI as the key and the Controller@method as the value.
        '/' => 'ControllerClassName@controllerMethod',
        
        # Some examples are below
        'hello' => 'HelloController@index',
        'hello/:id' => 'HelloController@show'
    ]
];

# Register your routes in the SlimController.
$controller->routes($routes);

# Run your application.
$app->run();
```

Licence
-------
The Slim Controller is open-sourced software licensed under the [MIT Licence](http://opensource.org/licenses/MIT).
