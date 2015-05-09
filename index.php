<?php

include 'api/vendor/autoload.php';

include 'api/lib/common.php';
include 'api/conf/config.php';

$app = new \Slim\Slim(array(
    'debug' => true,
    'view' => new \Slim\Views\Twig,
    'templates.path' => 'api/views',
));

$db = DB(DB_DSN);

$view = $app->view;
$view->parserOptions = array(
    'debug' => true,
    'cache' => 'api/var/cache',
);

$app->get('/', function() use ($app, $db) {
  $app->render('index/index.twig');
});

$app->get('/api/here', 'API', function() use ($app, $db) {
    $res = $db->query('SELECT NOW()');
    
    $time = 'NA';
    
    if ($res) {
        $time = $res->fetchColumn();
    }
    
    die(var_dump($time));

});

$app->run();