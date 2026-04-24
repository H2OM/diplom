<?php

use app\core\Container;
use app\core\Router;
use app\core\App;

require_once "./settings/config.php";
require_once LIB . "functions.php";

session_start();

App::init(new Container());
App::container()->setVariable('db_config', require CONF . 'config_db.php');

if(App::request()->server('API_CONTROLLER') && App::request()->server('API_ACTION')) {
    Router::dispatch(
        controller: App::request()->server('API_CONTROLLER'),
        action: App::request()->server('API_ACTION')
    );
} else {
    Router::dispatchURI(App::request()->server('REQUEST_URI'));
}
