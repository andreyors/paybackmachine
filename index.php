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

$app->post('/api/here', 'API', function() use ($app, $db) {
    $res = $db->query("SELECT id, client_id FROM events ORDER BY id DESC LIMIT 1");
    $details = false;
    if ($res) {
        $details = $res->fetch();
    }
    
    $input = file_get_contents('php://input');
    $data = json_decode($input, true);
    
    $current_client_id = !empty($data['client_id']) ? $data['client_id'] : false;
    $client_id = !empty($details['client_id']) ? $details['client_id'] : false;
    
    if ($current_client_id && ($current_client_id == $client_id)) {
        $id = !empty($details['id']) ? $details['id'] : false;
        $sql = sprintf("UPDATE events SET updated_at = NOW() WHERE id = %d", $id);
    } else {
        $sql = sprintf("SELECT id FROM beacons WHERE mac = '%s' AND minor = '%s' AND major = '%s'", $data['mac'], $data['minor'], $data['major']);
        $res = $db->query($sql);
        
        $beacon_id = false;
        if ($res) {
            $beacon_id = $res->fetchColumn();
        }
        
        if (!$beacon_id) {
            $sql = sprintf("INSERT INTO beacons (mac, minor, major) VALUES ('%s', '%d', '%d') RETURNING id", $data['mac'], $data['minor'], $data['major']);
            
            $res = $db->query($sql);
            if ($res) {
                $beacon_id = $res->fetchColumn();
            }
        }
        
        if ($beacon_id) {
            $sql = sprintf("INSERT INTO events (client_id, beacon_id, created_at, updated_at) VALUES ('%s', '%d', NOW(), NOW())", $data['client_id'], $beacon_id);    
        }
    }
    
    $db->exec($sql);
    
    $app->render(200, array('response' => array('success' => true, 'sql' => $sql)));
});

$app->run();