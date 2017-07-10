<?php
/**
 * Application front controller for `dev` environment.
 */

ini_set('error_reporting', E_ALL);
ini_set('display_errors', true);

if (isset($_SERVER['HTTP_CLIENT_IP'])
    || isset($_SERVER['HTTP_X_FORWARDED_FOR'])
    || !in_array(@$_SERVER['REMOTE_ADDR'], array('127.0.0.1', 'fe80::1', '::1'))
) {
    header('HTTP/1.0 403 Forbidden');
    exit('You are not allowed to access this file. Check '.basename(__FILE__).' for more information.');
}

require_once dirname(dirname(__FILE__)).'/vendor/autoload.php';

$app = require_once dirname(dirname(__FILE__)).'/src/app.php';

$app['debug'] = true;

require_once dirname(dirname(__FILE__)).'/src/controllers.php';

$app->run();
