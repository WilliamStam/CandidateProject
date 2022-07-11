<?php

namespace System\Messages;

interface MessageInterface {
    function setType(string $value);
    function setMessage(string $value);
    function toArray() : array;
}