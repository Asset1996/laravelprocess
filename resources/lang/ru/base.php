<?php 
return [
    "headers" => [
        'passLogs' => [
            'list' => [
                'No' => 'No',
                'Full Name' => 'ФИО',
                'Action' => 'Действие',
                'Photo' => 'Фото',
                'DateTime' => 'Время',
            ]
        ],
        'user' => [
            'list' => [
                'No' => 'No',
                'Full Name' => 'ФИО',
                'iin' => 'ИИН',
                'role' => 'Роль',
                'is_on_duty' => 'Локация',
                'DateTime' => 'Время',
            ],
            'timingList' => [
                'date' => 'Дата',
                'time' => 'Часы и минуты'
            ]
        ]
    ],
    "direction" => [
        '1' => 'Вход',
        '0' => 'Выход',
    ],
    "is_on_duty" => [
        '1' => 'На работе',
        '0' => 'Не на работе',
    ],
];