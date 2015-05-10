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

$app->get('/my', function() use ($app, $db) {
    $app->render('index/my.twig');
});

$app->get('/cashdesk', function() use($app, $db) {
    $data['name'] = 'John Doe';
    $data['percent'] = 13;
    $data['text'] = 'Hi_ecomhack2015';
    
    $sql = sprintf('SELECT state_id FROM beacon_to_state WHERE beacon_id = %d', 1);
    $res = $db->query($sql);
    
    $state_id = false;
    if ($res) {
        $state_id = $res->fetchColumn();
    }

    $data['isDiscount'] = (1 == $state_id);
    
    $app->render('index/cashdesk.twig', $data);
});

$app->get('/ajax/greetings', function() use($app, $db) {
    $data['name'] = 'John Doe';
    $data['percent'] = 13;
    $data['text'] = 'Hi_ecomhack2015';
    
    $sql = sprintf('SELECT state_id FROM beacon_to_state WHERE beacon_id = %d', 1);
    $res = $db->query($sql);
    
    $state_id = false;
    if ($res) {
        $state_id = $res->fetchColumn();
    }
    $data['isDiscount'] = (1 == $state_id);
    
    $app->render('partials/greetings.twig', $data);
});

$app->post('/api/here', 'API', function() use ($app, $db) {
    $beacon_id = false;
    
    $input = file_get_contents('php://input');
    $data = json_decode($input, true);
    
    $res = $db->query("SELECT id, client_id, beacon_id FROM events ORDER BY id DESC LIMIT 1");
    $details = false;
    if ($res) {
        $details = $res->fetch();
    }
    
    $current_client_id = !empty($data['client_id']) ? $data['client_id'] : false;
    $client_id = !empty($details['client_id']) ? $details['client_id'] : false;
    
    if ($current_client_id && ($current_client_id == $client_id)) {
        $id = !empty($details['id']) ? $details['id'] : false;
        $sql = sprintf("UPDATE events SET updated_at = NOW() WHERE id = %d", $id);
        
        $beacon_id = !empty($details['beacon_id']) ? $details['beacon_id'] : false;
    } else {
        $sql = sprintf("SELECT id FROM beacons WHERE (mac = '%s') AND (minor = '%d') AND (major = '%d')", $data['mac'], $data['minor'], $data['major']);
        $res = $db->query($sql);
        
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
    
        $sql = false;    
        if ($beacon_id) {
            $sql = sprintf("INSERT INTO events (client_id, beacon_id, created_at, updated_at) VALUES ('%s', '%d', NOW(), NOW())", $data['client_id'], $beacon_id);    
        }
    }
    
    if (!empty($sql)) {
        $db->exec($sql);
    }
    
    if ($beacon_id) {
        $sql = sprintf('SELECT state_id FROM beacon_to_state WHERE beacon_id = %d', $beacon_id);
        $res = $db->query($sql);
        
        if ($res) {
            $state_id = $res->fetchColumn();
        
            $sql = false;
            if ($state_id != 1) {
                $sql = sprintf("UPDATE beacon_to_state SET state_id = %d WHERE beacon_id = %d", 1, $beacon_id);
            }
        } else {
            $sql = sprintf('INSERT INTO beacon_to_state (state_id, beacon_id) VALUES (%d, %d)', 1, $beacon_id);
        }
        
        if (!empty($sql)) {
            $db->exec($sql);    
        }
    }
    
    $app->render(200, array('response' => array('success' => true, 'sql' => $sql)));
});

$app->post("/api/left", 'API', function() use($app, $db) {
    $input = file_get_contents('php://input');
    $data = json_decode($input, true);
    
    $sql = sprintf("SELECT id FROM beacons WHERE (mac = '%s') AND (minor = '%d') AND (major = '%d')", $data['mac'], $data['minor'], $data['major']);
    $res = $db->query($sql);
    
    $beacon_id = false;
    if ($res) {
        $beacon_id = $res->fetchColumn();
    }    
   
    if ($beacon_id) {
        $sql = sprintf('SELECT state_id FROM beacon_to_state WHERE beacon_id = %d', $beacon_id);
        $res = $db->query($sql);
        
        if ($res) {
            $state_id = $res->fetchColumn();
        
            $sql = false;
            if ($state_id != 2) {
                $sql = sprintf("UPDATE beacon_to_state SET state_id = %d WHERE beacon_id = %d", 2, $beacon_id);
            }
        } else {
            $sql = sprintf('INSERT INTO beacon_to_state (state_id, beacon_id) VALUES (%d, %d)', 2, $beacon_id);
        }
        
        if (!empty($sql)) {
            $db->exec($sql);    
        }
    }
    
    $app->render(200, array('response' => array('success' => true, 'sql' => $sql)));
});

$app->run();