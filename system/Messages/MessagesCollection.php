<?php

namespace System\Messages;

use System\Helpers\Collection;


class MessagesCollection extends Collection {
    function info($message){
        return $this->add(new MessageItem(MessageTypes::INFO,$message));
    }
    function success($message){
        return $this->add(new MessageItem(MessageTypes::SUCCESS,$message));
    }
    function warning($message){
        return $this->add(new MessageItem(MessageTypes::WARNING,$message));
    }
    function error($message){
        return $this->add(new MessageItem(MessageTypes::ERROR,$message));
    }

}

