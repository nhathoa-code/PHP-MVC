<?php 

define("ROOT_DIR", '/mvc/public');

define("ROOT_PATH", dirname(__DIR__));

define("VIEW_PATH", dirname(__DIR__) . "/views");

define("PUBLIC_PATH", dirname(__DIR__) . "/public");

// Database Configuration
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'mvc');

// -------------------------
define('ENVIRONMENT', 'dev');

// Email Configuration
define('HOST', '');
define('USERNAME', '');
define('PASSWORD', '');
define('SMTP', 'tls');
define('FROM_EMAIL', '');
define('FROM_NAME', '');

// Time zone
date_default_timezone_set('Asia/Ho_Chi_Minh');