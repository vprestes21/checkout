<?php
/**
 * Main configuration file for the CheckoutPro application
 */

// Database configuration
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'checkoutpro');

// Application settings
define('SITE_NAME', 'CheckoutPro');
define('APP_ROOT', dirname(dirname(__FILE__)));
define('URL_ROOT', '/checkout');
define('URL_SUBFOLDER', '');

// Set error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Set default timezone
date_default_timezone_set('America/Sao_Paulo');

// Remove duplicate getFlash function - this is now defined in core/helpers.php
