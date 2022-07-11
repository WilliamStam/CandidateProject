<?php

namespace System\Profiler;
use System\Helpers\Collection;

class ProfilerCollection extends Collection {
    function start(string $label, string $component = ""): ProfilerItem {
        return $this->add((new ProfilerItem($label, $component)));
    }

    function getTotalTime() {
        $start = $this->first()->getTimeStart();
        $end = $this->last()->getTimeEnd();

        return ($end - $start) * 1000;
    }
    function getTotalMemory() {
        $start = $this->first()->getMemoryStart();
        $end = $this->last()->getMemoryEnd();

        return ($end - $start);
    }

    function toArray() : array {
        $return = array();
        foreach ($this as $item) {
            $return_item = array(
                "label" => $item->getLabel(),
                "component" => $item->getComponent(),
                "time" => $item->getTime(),
                "memory" => $item->getMemory(),
            );
            $return[] = $return_item;
        }
        return $return;
    }
}