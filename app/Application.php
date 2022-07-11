<?php

use DI\ContainerBuilder;
use Slim\App;




require_once __DIR__ . '/../vendor/autoload.php';

$containerBuilder = new ContainerBuilder();

// container definitions
$containerBuilder->addDefinitions(__DIR__ . '/Container.php');
$containerBuilder->addDefinitions(__DIR__ . '/Config.php');

$containerBuilder->useAutowiring(true);
$containerBuilder->useAnnotations(false);

$container = $containerBuilder->build();
$app = $container->get(App::class);

// naming some class to app namespaces for easier use
// im way too lazy to use the FQDN every time
class_alias("\League\Config\Configuration", 'App\Config');
class_alias("\System\Profiler\ProfilerCollection", 'App\Profiler');
class_alias("\System\Messages\MessagesCollection", 'App\Messages');
class_alias("\System\Errors\ErrorsCollection", 'App\Errors');
class_alias("\System\Events\EventsEmitter", 'App\Events');
class_alias("\Illuminate\Database\Capsule\Manager", 'App\DB');
class_alias("\Psr\Log\LoggerInterface", 'App\Log');


class_alias("\Psr\Http\Message\ResponseInterface", 'App\Response');
class_alias("\Psr\Http\Message\ServerRequestInterface", 'App\Request');



// split into files for easier working with them. but they need a $app to work
(require __DIR__ . '/Middleware.php')($app);
(require __DIR__ . '/DB.php')($app);
(require __DIR__ . '/Routes.php')($app);
(require __DIR__ . '/Events.php')($app);


$app->getContainer()->get(\App\Events::class)->emit("app.start");

return $app;