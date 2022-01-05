<?php

require_once 'source/vendor/autoload.php';

use Slim\Factory\AppFactory;

use App\Controllers\ControllerImage;

$app = AppFactory::create();

$app->addErrorMiddleware(true, true, true);

$app->setBasePath('/php-rest-api-slim');

$app->get('/', ControllerImage::class . ':read');

$app->run();





