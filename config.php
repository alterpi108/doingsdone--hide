<?php

return [
    'database' => [
        'name' => 'doingsdone',
        'username' => 'root',
        'password' => '100100',
        'connection' => 'mysql:host=127.0.0.1',
        'options' => [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        ]
    ]
];
