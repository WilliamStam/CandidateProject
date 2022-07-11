<?php 
namespace update\system;

class git extends Run {

    function run(){
        $this->label("FILES",0);

        if ($this->is_dev()){
            echo "running in dev mode so wont do updates".PHP_EOL;
        } else {
            $this->reset();
            $this->install();
        }
        

    }

    
    function reset(){
        $cmd = "git reset HEAD --hard";
        $this->exec($cmd);
    }
    function install(){
        $git_config = $this->config()['git'];
        $cmd = "git pull {$git_config['host']} {$git_config['branch']}";
        $this->exec($cmd);
    }
    

}
