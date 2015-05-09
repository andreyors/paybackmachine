<?php
/**
 * JSON instrumentation
 */
function API() {
  $app = \Slim\Slim::getInstance();
  $app->add(new \SlimJson\Middleware([
    'json.status' => true,
    'json.debug' => false, 
    'json.override_error' => true,
    'json.override_notfound' => true
  ]));
}

/**
 * Database connection
 */
function DB($db_dsn) {
    $dbh = new PDO($db_dsn);  
    
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $dbh->setAttribute(PDO::MYSQL_ATTR_INIT_COMMAND, 'SET NAMES utf8');
    $dbh->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    
    return $dbh;
}