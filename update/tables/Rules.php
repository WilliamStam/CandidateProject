<?php


return [

    "subscriptions" => array(
        "msisdn" => array(
            "Rules\isNumber::class",
            "[Rules\StartsWith::class,27]",
            "[Rules\MinLength::class,11]",
        ),
        "service_id" => array(
            "Rules\isNumber::class",
        ),
        "is_active" => array(
            "Rules\isNumber::class",
        ),

    ),
];

