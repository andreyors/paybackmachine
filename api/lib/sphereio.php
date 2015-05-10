<?php

class SphereIO {
  /**
   * 
   */
  public function __construct($client_id, $client_secret, $project_key) {
    $client_id = trim($client_id);
    $client_secret = trim($client_secret);
    $project_key = trim($project_key);
    
    $authUrl = "https://$client_id:$client_secret@auth.sphere.io/oauth/token";
    $data = array("grant_type" => "client_credentials", "scope" => "manage_project:$project_key");
    $options = array(
      'http' => array(
        'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
        'method'  => 'POST',
        'content' => http_build_query($data),
      ),
    );
    $context = stream_context_create($options);
    $authResult = $this->makeRequest($authUrl, $context);
    
    $this->access_token = $authResult["access_token"];  
    $this->project_key = $project_key;
  }
  
  /**
   * 
   */
  public function makeRequest($url, $context) {
    $fp = fopen($url, 'rb', false, $context);
    if (!$fp) {
      throw new Exception("Problem with $url");
    }
    // get the response and decode
    $response = stream_get_contents($fp);
    if ($response === false) {
      throw new Exception("Problem reading data from $url");
    }
    $result = json_decode($response, true);
    // close the response
    fclose($fp);
    
    return $result;
  }
  
  /**
   * 
   */
  public function getProducts() {
    $access_token = $this->access_token;
    $project_key = $this->project_key;
    
    // Fetch products
    $productUrl = "https://api.sphere.io/$project_key/product-projections";
    $options = array(
      'http' => array(
        'header'  => "Authorization: Bearer $access_token",
        'method'  => 'GET'
      ),
    );
    
    $c = stream_context_create($options);
    $result = $this->makeRequest($productUrl, $c); // array
    
    return $result;
  }
  
  /**
   * 
   */
  public function getOrders() {
    $access_token = $this->access_token;
    $project_key = $this->project_key;
    
    $orderUrl = "https://api.sphere.io/$project_key/orders";
    $options = array(
      'http' => array(
        'header' => "Authorization: Bearer $access_token",
        'method' => 'GET'
      ),
    );
    
    $c = stream_context_create($options);
    $result = $this->makeRequest($orderUrl, $c);
    
    return $result;
  }
  
  /**
   * 
   */
  public function getCustomers() {
    $access_token = $this->access_token;
    $project_key = $this->project_key;
    
    $orderUrl = "https://api.sphere.io/$project_key/customers";
    $options = array(
      'http' => array(
        'header' => "Authorization: Bearer $access_token",
        'method' => 'GET'
      ),
    );
    
    $c = stream_context_create($options);
    $result = $this->makeRequest($orderUrl, $c);
    
    return $result;
  }
}

