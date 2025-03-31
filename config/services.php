<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],
    "PossibleSelectedOptions" => [
        "leaf1_glass_type" => " Glass Type",
        "leaf1_glass_thickness" => "Glass Thickness",
        "door_leaf_facing_value" => "Door Leaf Facing Value",
        "Glazing_System" => "Glazing System",
        "Accoustics" => "Accoustics",
        "Architrave" => "Architrave",
        "Door_Leaf_Facing" => "Door Leaf Facing",
        "door_leaf_finish" => "Door Leaf Finish",
    ],
];
