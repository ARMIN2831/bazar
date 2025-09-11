<?php
return[
    'custom' => [
        'currency_id' => [
            'required_if' => 'The currency field is required when user type is not driver',
        ],
        'city_id' => [
            'required_if' => 'The city field is required for drivers',
        ],
        'car_type' => [
            'required_if' => 'The car type field is required for drivers',
        ],
    ],



    'attributes' => [
        'work_id' => 'Work ID',
        'sitePercent' => 'Site percent',
        'nationalCode' => 'national code',
        'email_mobile' => 'email or mobile',
        'car_type' => 'car type',
        'newPassword' => 'new password',
        'city_id' => 'province',
        'province_id' => 'city',
        'village_id' => 'village',
        'currency_id' => 'currency',
        'dest_currency_id' => 'Destination currency',
        'file_id' => 'file',
        'birth' => 'Date of birth',
        'nationality' => 'Date of birth',
        'preferred_language' => 'preferred language',
        'current_medical_issue' => 'current medical issue',
        'previous_conditions' => 'previous conditions',
        'current_treatment' => 'current treatment',
        'consultation_type' => 'consultation type',
        'scheduled_call_time' => 'scheduled call time',
        'lan_id' => 'language',
        'country_id' => 'country',
    ],
];
