<?php

$dispatcher = FastRoute\simpleDispatcher(function(FastRoute\RouteCollector $r) {
	$r->addRoute('GET', '/index', function() {
		echo 222;
	});
	$r->addRoute('GET', '/index/{id}', function() {
		echo 333;
	});
    // $r->addRoute('GET', '/users', 'get_all_users_handler');
    // // {id} must be a number (\d+)
    // $r->addRoute('GET', '/user/{id:\d+}', 'get_user_handler');
    // // The /{title} suffix is optional
    // $r->addRoute('GET', '/articles/{id:\d+}[/{title}]', 'get_article_handler');
});

// Fetch method and URI from somewhere
$httpMethod = $_SERVER['REQUEST_METHOD'];
$uri = $_SERVER['REQUEST_URI'];

// Strip query string (?foo=bar) and decode URI
if (false !== $pos = strpos($uri, '?')) {
    $uri = substr($uri, 0, $pos);
}
$uri = rawurldecode($uri);

$routeInfo = $dispatcher->dispatch($httpMethod, $uri);
print_r($routeInfo);
switch ($routeInfo[0]) {
    case FastRoute\Dispatcher::NOT_FOUND:
        // ... 404 Not Found
        break;
    case FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
        $allowedMethods = $routeInfo[1];
        // ... 405 Method Not Allowed
        break;
    case FastRoute\Dispatcher::FOUND:
        $handler = $routeInfo[1];
        $vars = $routeInfo[2];
        routeHadler($handler, $vars);
        // ... call $handler with $vars
        break;
}

function routeHadler($handler, $var) {
	if (is_callable($handler)) {
		$handler();
	}
}