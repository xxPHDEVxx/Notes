<?php
date_default_timezone_set('Europe/Brussels');

error_reporting( E_ALL );
ini_set( 'display_errors', 1 );

require_once 'framework/Router.php';
require_once 'framework/Configuration.php';

if(!Configuration::is_dev()){
    ini_set( 'display_errors', 0 );
}

(new Router())->route();
