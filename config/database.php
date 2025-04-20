<?php

return [
    'host' => 'localhost',
    'database' => 'pomodoro_app',
    'username' => 'root', // Change this in production
    'password' => 'wijaya28@',     // Change this in production
    'options' => [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ]
]; 