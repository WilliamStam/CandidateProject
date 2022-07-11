<?php

namespace App\Domain\Subscription\Models;

use App\Profiler;
use App\Tables\SubscriptionsTable;
use App\Tables\SubscriptionsView;
use System\Models\AbstractModel;

class SubscriptionModel extends AbstractModel {
    public function __construct(
        protected Profiler $profiler
    ) {
        $this->model = new SubscriptionsView();
        $this->saveModel = new SubscriptionsTable();
    }


    function save(array $values = array()) : SubscriptionModel {
        $profiler = $this->profiler->start(__CLASS__ . "::" . __FUNCTION__, __NAMESPACE__);

         $return = $this->saveModel->updateOrCreate(
            ["uuid" => $this->model->uuid],
            $values
        );
         foreach ($values as $key=>$value){
             $this->model->$key = $value;
         }

        $profiler->stop();
        return $this;
    }
    function validate(array $values = array()){

        return $this->saveModel->validate($values);
    }

}