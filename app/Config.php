<?php

use App\Config;
use App\ErrorHandler;
use App\Modules;
use Psr\Container\ContainerInterface;
use System\Utilities\Strings;
use Nette\Schema\Expect;


return [
    Config::class => function (ContainerInterface $container) {
        error_reporting(0);
        ini_set('display_errors', '0');
        date_default_timezone_set('Africa/Johannesburg');

        $root = dirname(__DIR__);
        $storage = Strings::fixDirSlashes($root . DIRECTORY_SEPARATOR . "/storage");

        $config = new Config(array(
            "dev" => Expect::bool()->default(false),
            "storage" => Expect::string()->default($storage),
            "logs" => Expect::structure(array(
                "errors" => Expect::string()->default(Strings::fixDirSlashes($storage . "/logs"))
            )),
            "db" => Expect::structure(array(
                "host" => Expect::string()->default('localhost'),
                "port" => Expect::int()->min(1)->max(65535),
                "database" => Expect::string(),
                "username" => Expect::string(),
                "password" => Expect::string(),
                'flags' => Expect::array()->default([
                    PDO::ATTR_PERSISTENT => false,
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_EMULATE_PREPARES => true,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_STRINGIFY_FETCHES => true,
                ]),
            )),
            "git" => Expect::structure(array(
                "branch" => Expect::string()->default("master"),
                "host" => Expect::string()->default("origin")
            )),
        ));

        $configFile = realpath("../config.php");

        if (file_exists($configFile)) {
            $my_config = require($configFile);
            $config->merge($my_config);
        }

        return $config;
    }
];