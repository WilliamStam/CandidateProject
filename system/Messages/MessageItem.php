<?php

namespace System\Messages;

class MessageItem implements MessageInterface {

    function __construct(
        protected $type,
        protected $message
    ) {
    }

    function setType(string $value): MessageItem {
        $this->type = $value;
        return $this;
    }

    function setMessage(string $value): MessageItem {
        $this->message = $value;
        return $this;
    }

    function toArray(): array {
        return array(
            "type" => $this->type,
            "message" => $this->message,
        );
    }
}