<?php

namespace PeanutLabs\SampleApiSDK\Http;

class Curl {
  /**
   * @var resource Curl resource instance
   */
  protected $curl = null;
  
  public function init() {
    if ($this->curl === null) {
        $this->curl = curl_init();
    }
  }
  
  public function setOption($key, $value) {
    curl_setopt($this->curl, $key, $value);
  }
  
  public function setOptionArray(array $options) {
    curl_setopt_array($this->curl, $options);
  }
  
  public function exec() {
    return curl_exec($this->curl);
  }
  
  public function errno() {
    return curl_errno($this->curl);
  }
  
  public function error() {
    return curl_error($this->curl);
  }
  
  public function getInfo($type=null) {
    $info = curl_getinfo($this->curl);
    return $type ? $info[$type] : $info;
  }
  
  public function version() {
    return curl_version();
  }
  
  public function close() {
    curl_close($this->curl);
    $this->curl = null;
  }
}