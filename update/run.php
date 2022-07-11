<?php


namespace update;


spl_autoload_register(function ($class) {
    $root = realpath("../");

    $path = $root . DIRECTORY_SEPARATOR . $class;
    $path = str_replace(array("\\", "/", "//", "\\\\"), DIRECTORY_SEPARATOR, $path);
    $path = $path . ".php";

    if (file_exists($path)) {
        include($path);
    } else {
        return FALSE;
    }
});


$available = [
    system\Run::class,
    database\Run::class,
    tables\Run::class,
];


foreach ($available as $cmd) {
    if ($cmd::cmd()) {
        $cmds[$cmd::cmd()] = $cmd;
    }

}

if (isset($argv[1]) && in_array($argv[1], array_keys($cmds))) {
    (new $cmds[$argv[1]])->run();
} else {
    (new system\Run())->run();
    (new database\Run())->run();
}



