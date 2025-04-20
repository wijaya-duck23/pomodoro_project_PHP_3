<?php

// Enable error reporting for development
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Start session
session_start();

// Set timezone
$config = require 'config/config.php';
date_default_timezone_set($config['timezone']);

// Load routes
require_once 'routes/web.php'; 