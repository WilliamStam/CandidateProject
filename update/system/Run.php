<?php 


namespace update\system;

use \update\AbstractBase;
use \update\RunInterface;


class Run extends AbstractBase implements RunInterface {
    static function cmd(){
        return "system";
    }
    function run(){


        $this->label("UPDATING SYSTEM",0);

        $timer = (double) microtime(TRUE);

        (new git())->run();
        (new composer())->run();


        $ended = (double) microtime(TRUE);
        $this->label("Finished: ". ($ended - $timer));
        
    }

}
