<?php

/*
 *---------------------------------------------------------------
 * APPLICATION SPECIFIC VALUES
 *---------------------------------------------------------------
 *
 * Application specific testing values
 */


global $username;
$username = 'phpunit';

$test_files = array(
    'STATUSFILE'  => dirname(__FILE__).'/files/status.dat',
    'OBJECTSFILE' => dirname(__FILE__).'/files/objects.cache'
);

foreach($test_files as $constant => $path)
{
    if( file_exists($path) && is_readable($path) )
    {
        define($constant, $path); 
    }
}

/*
 *---------------------------------------------------------------
 * OVERRIDE FUNCTIONS
 *---------------------------------------------------------------
 *
 * This will "override" later functions meant to be defined
 * in core\Common.php, so they throw erros instead of output strings
 */

function show_error($message, $status_code = 500, $heading = 'An Error Was Encountered')
{
    throw new PHPUnit_Framework_Exception($message, $status_code);
}

function show_404($page = '', $log_error = TRUE)
{
    throw new PHPUnit_Framework_Exception($page, 404);
}

/*
 *---------------------------------------------------------------
 * BOOTSTRAP
 *---------------------------------------------------------------
 *
 * Bootstrap CodeIgniter from index.php as usual
 */

require_once dirname(__FILE__) . '/../../index.php';

/*
 * This will autoload controllers inside subfolders
 */ 
spl_autoload_register(function ($class) {
    foreach (glob(APPPATH.'controllers/**/'.strtolower($class).'.php') as $controller) {
        require_once $controller;
    }
});

/*
 *---------------------------------------------------------------
 * ERROR REPORTING
 *---------------------------------------------------------------
 *
 * Set error_reporting here instead of mucking around with logic
 * inside index.php
 */

error_reporting(E_ALL);
