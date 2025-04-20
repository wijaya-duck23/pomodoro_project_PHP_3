<?php

return [
    // Timer settings (in seconds)
    'pomodoro_duration' => 25 * 60,        // 25 minutes
    'short_break_duration' => 5 * 60,      // 5 minutes
    'long_break_duration' => 15 * 60,      // 15 minutes
    'long_break_interval' => 4,            // After every 4 pomodoros
    
    // Application settings
    'app_name' => 'Pomodoro Timer',
    'base_url' => 'http://202.10.44.29/',
    'timezone' => 'UTC',
    
    // Session settings
    'session_expiry' => 1800,              // 30 minutes
]; 