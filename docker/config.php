<?php


ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
date_default_timezone_set('Africa/Johannesburg');

// the updater doesn't use the default values, it only uses these so DB and git stuff are requirements
return [
     "db"=>array(
        "host"=>"mysql",
        "port"=>3307,
        "database"=>"app",
        "username"=>"app",
        "password"=>"app",
    ),
     "git" => array(
        "branch" => "master",
        "host" => "origin"
    ),
];


