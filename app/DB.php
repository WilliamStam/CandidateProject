<?php


use App\Config;
use Illuminate\Database\Query\Builder;
use Slim\App;
use App\DB;


//class DB extends \Illuminate\Database\Capsule\Manager {}

return function (App $app) {

    $container = $app->getContainer();
    $config = $container->get(Config::class);

    $db_container = new \Illuminate\Container\Container;
    $db_container->bind(Config::class,function() use ($container){
        return $container->get(Config::class);
    });

    $capsule = new DB($db_container);
    $capsule->addConnection([
        'charset' => 'utf8mb4',
        'collation' => 'utf8mb4_unicode_ci',
        'prefix' => '',

        "driver" => "mysql",
        "host" => $config->get("db.host"),
        "database" => $config->get("db.database"),
        "username" => $config->get("db.username"),
        "password" => $config->get("db.password"),
        "options" => [
            \PDO::ATTR_EMULATE_PREPARES => true
        ]
    ]);
    $capsule->setAsGlobal();
    $capsule->bootEloquent();

    DB::connection()->enableQueryLog();

};

