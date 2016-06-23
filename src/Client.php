<?php

namespace PeanutLabs\SampleApiSDK;
use \PeanutLabs\SampleApiSDK\Http\Request;
use \PeanutLabs\SampleApiSDK\Http\Response;
use \PeanutLabs\SampleApiSDK\Http\Auth\Hmac;
use \PeanutLabs\SampleApiSDK\RequestException;

class Client {
  
  const VERSION = '1.0';
    
  const API_VERSION = 'v1';
  
  protected $request;
  protected $response;
  protected $clientId;
  protected $secretKey;
  protected $host;
  
  public function __construct($client_id, $secret_key, $host) {
    $this->clientId = $client_id;
    $this->secretKey = $secret_key;
    $this->host = $host;
    $this->request = new Request();
    $this->response = new Response();
  }
  
  public function sendRequest($method, $path, $params) {
    $this->encodeParams($params);
    $uri = $this->generateUri($path);
    $auth_header = $this->createAuthHeader($method, $path, $params);
    $this->request->addHeader('Authorization', $auth_header);
    $this->request->addHeader('Content-Type', 'application/json');
    $this->request->setParams($params);
    $this->request->sendRequest($method, $uri);
    return $this->getResponse();
  }
  
  private function encodeParams(&$params) {
    foreach ($params as $key => $val) {
      if (is_array($val)) {
        $params[$key] = json_encode($val);
      }
    }
  }
  
  private function getResponse() {
    $raw_response = $this->request->getRawResponse();
    $response_meta = $this->request->getResponseMeta();
    $this->response->setRawResponse($raw_response)
      ->setResponseMeta($response_meta)
      ->formatResponse();
    /*
    if (!$this->response->isSuccess()) {
      throw new RequestException($this->response->getErrorMessage());
    }*/
    return $this->response;
  }
  
  private function createAuthHeader($method, $path, $params) {
    $action = $method . $path;
    $timestamp = time();
    $hmac_auth = new Hmac($this->secretKey);
    $hmac_auth->setParams($params)
      ->setAction($action)
      ->setTimeStamp($timestamp);
    $hash = $hmac_auth->hash();
    return "{$this->clientId}:{$timestamp}:{$hash}"; 
  }
    
  private function generateUri($path) {
      return $this->host . $path;
  }
  
}
