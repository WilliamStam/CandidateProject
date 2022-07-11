<?php 
namespace update\system;

use \update\AbstractBase;
use \update\RunInterface;

class composer extends Run {


    function run(){
        $this->label("COMPOSER",0);

//        $cwd = getcwd();
//        $root = realpath("../");
//        chdir($root);
        if ($this->is_dev()){
            $this->update();
        } else {
            $this->install();
        }
//        chdir($cwd);
    }
    function composer_dir(){
        return realpath("../");
    }
    
    function update(){
        $cmd = "composer update --no-cache --working-dir=".$this->composer_dir();
        $this->exec($cmd);
    }
    function install(){
        $cmd = "composer install --no-cache --working-dir=".$this->composer_dir();
        $this->exec($cmd);
    }
    
    

}
