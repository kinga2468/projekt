<?php
/**
 * Application front controller for `production` environment.
 */

ini_set('error_reporting', E_ALL);
ini_set('display_errors', false);

require_once dirname(dirname(__FILE__)).'/vendor/autoload.php';

$app = require_once dirname(dirname(__FILE__)).'/src/app.php';

require_once dirname(dirname(__FILE__)).'/src/controllers.php';

$app->run();
