<?php

namespace App\Domain\Log\Models;

use App\Profiler;
use App\Tables\LogsTable;
use System\Models\AbstractModel;

class LogModel extends AbstractModel {

    /**
     * @param Profiler $profiler
     */
    public function __construct(
        protected Profiler $profiler
    ) {
        $this->model = new LogsTable();
    }

    /**
     * @param array $values
     * @return $this
     */
    function create(array $values = array()): self {
        $profiler = $this->profiler->start(__CLASS__ . "::" . __FUNCTION__, __NAMESPACE__);
        $this->model = $this->model->create($values);
        $profiler->stop();
        return $this;
    }

    /**
     * @param array $values
     * @return $this
     */
    function save(array $values = array()): self {
        $profiler = $this->profiler->start(__CLASS__ . "::" . __FUNCTION__, __NAMESPACE__);
        $this->model = $this->model->updateOrCreate(
            ["id" => $this->model->id],
            $values
        );
        $profiler->stop();
        return $this;
    }

    /**
     * @param array $values
     * @return array
     */
    function validate(array $values = array()): array {
        $profiler = $this->profiler->start(__CLASS__ . "::" . __FUNCTION__, __NAMESPACE__);
        $errors = $this->model->validate($values);
        $profiler->stop();
        return $errors;
    }

    /**
     * @return bool|null
     */
    function delete() {
        $profiler = $this->profiler->start(__CLASS__ . "::" . __FUNCTION__, __NAMESPACE__);
        $return = false;
        if ($this->model->id) {
            $return = $this->model->delete();
        }
        $profiler->stop();
        return $return;
    }


}