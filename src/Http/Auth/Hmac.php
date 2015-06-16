<?php

namespace Peanutlabs\SampleApiSDK\Http\Auth;

class Hmac {
  
  private $secretKey;
  private $params;
  private $action;
  private $timeStamp;
  
  public function __construct($secret_key) {
    $this->params = array();
    $this->secretKey = $secret_key;
  }
    
  public function setParams($params) {
    $this->params = $params;
    return $this;
  }
  
  public function setAction($action) {
    $this->action = $action;
    return $this;
  }
  
  public function setTimeStamp($timestamp) {
    $this->timeStamp = $timestamp;
    return $this;
  }
  
  public function hash() {
    $string_to_hash = $this->prepareParams($this->params);
    $hash_algorithm = $this->getHashAlgorithm();
    return hash_hmac($hash_algorithm, $string_to_hash, $this->secretKey);
  }
  
  protected function prepareParams($params) {
    $this->sortParams($params);
    $ordered_values = '';
    foreach ($params as $key => $val) {
      $ordered_values .= $val;
    }
    return $this->action . $ordered_values . $this->timeStamp;
  }
  
  protected function sortParams(&$params) {
    ksort($params);
  }
  
  protected function getHashAlgorithm() {
      return 'sha256';
  }
  
} 