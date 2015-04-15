<?php

// Load classes
require 'vendor/autoload.php';
require 'config.php';

// Init Slim
$app = new \Slim\Slim($config);

// Add middleware
$app->add(new \Api\Middleware\Cache);

// Set up routes
$app->hook('slim.before.router', new \Api\Routes\Router(array(
    new \Api\Routes\HomeRoute,
    new \Api\Routes\GitHubRoute,
    new \Api\Routes\LastFmRoutes,
    new \Api\Routes\FlickrRoute,
    new \Api\Routes\GoodreadsRoute,
    new \Api\Routes\SpotifyRoutes,
)));

// Go!
$app->run();