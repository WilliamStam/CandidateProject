<?php

namespace System\Profiler;

class ProfilerItem {
    protected $label;
    protected $component;

    protected $time_start;
    protected $time_end;
    protected $mem_start;
    protected $mem_end;

    function __construct(string $label = null, string $component = null) {
        $this->setlabel($label);
        $this->setComponent($component);

        $this->time_start = $this->_time();
        $this->mem_start = $this->_memory();
    }

    function setLabel($label) {
        $this->label = $label;
        return $this;
    }

    function getlabel() {
        return $this->label;
    }
    function setComponent($component) {
        $this->component = $component;
    }

    function getComponent() {
        return $this->component;
    }


    function _time() {
        return (double)microtime(TRUE);
    }

    function _memory() {
        return (double)memory_get_usage();
    }

    function stop(string $label = null) {
        if ($label) {
            $this->setlabel($label);
        }
        $this->time_end = $this->_time();
        $this->mem_end = $this->_memory();

        return $this;
    }

    function getTimeStart() {
        return $this->time_start;
    }

    function getTimeEnd() {
        return $this->time_end;
    }

    function getTime() {
        return ($this->getTimeEnd() - $this->getTimeStart()) * 1000;
    }

    function getMemoryStart() {
        return $this->mem_start;
    }

    function getMemoryEnd() {
        return $this->mem_end;
    }

    function getMemory() {
        return $this->getMemoryEnd() - $this->getMemoryStart();
    }
}