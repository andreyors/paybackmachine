<?php

include 'vendor/autoload.php';
include 'conf/config.php';

$app = new \Slim\Slim(array(
    'debug' => true,
    'view' => new \Slim\Views\Twig,
    'templates.path' => APP_ROOT . '/views',
));

$db = DB(DB_DSN, DB_USER, DB_PASS);

$view = $app->view;
$view->parserOptions = array(
    'debug' => true,
    'cache' => APP_ROOT . '/var/cache',
);

$app->get('/', function() use ($app, $db) {
  $app->render('index/index.twig');
});

$app->get('/api/register', 'API', function() use ($app, $db) {
  $app->render('index/workout.twig', array('like' => intval($like), 'cnt' => $cnt ));
});

$app->run();