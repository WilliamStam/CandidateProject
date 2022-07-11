<?php


namespace update;


abstract class AbstractBase {
    protected $CONFIG = false;

    function __construct() {

    }

    function is_dev(){

        return $this->config()['dev'] ?? false;
    }

    function config($force=false) {
        if (!$this->CONFIG || $force){
            $this->CONFIG = (new Config())->toArray();
        }
        return $this->CONFIG;
    }

    function label($label, $lvl = 0) {
        $prefix = "";
        if ($lvl == 0) {
            echo "-----------------------------------------------" . PHP_EOL;
        } else {
            $prefix = str_pad(" ", $lvl+1, "-", STR_PAD_LEFT);
        }

        echo $prefix . $label . PHP_EOL;
    }

    function exec($cmd) {
        echo " > " . $cmd . PHP_EOL;
        passthru($cmd);
    }


    function db() {
        $config = $this->config();
        return new DB($config['db']);
    }
    function startsWith($string, $startString) {
        $len = strlen($startString);
        return (substr($string, 0, $len) === $startString);
    }

}



