<?php

namespace update;

class Config {
    protected $data = array();

    function __construct() {
        $defaultConfig = array();
        $config = array();
        $configFile = realpath("../config.php");
        if (file_exists($configFile)) {
            $config = require($configFile);
        }
        $this->data = $config;
    }

    function toArray(): array {
        return $this->data;
    }


}