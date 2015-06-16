<?php

namespace Peanutlabs\SampleApiSDK\Http;

class Request {
  
  private $curl;
  private $headers;
  private $params;
  private $rawResponse;
  private $responseMeta;
  
  public function __construct() {
    $this->curl = new Curl();
    $this->headers = array();
    $this->params = array();
  }
  
  public function addHeader($header, $value) {
    $this->headers[$header] = $value;
  }
  
  public function setParams($params) {
    $this->params = $params;
  }
  
  public function getResponseHttpStatusCode() {
    return $this->responseHttpStatusCode;
  }
  
  public function getRawResponse() {
    return $this->rawResponse;
  }
  
  public function getResponseMeta() {
    return $this->responseMeta;
  }
  
  public function sendRequest($method = 'GET', $url) {
    $this->openConnection($method, $url);
    $this->send();
    $this->closeConnection();
  }
  
  private function send() {
    $this->rawResponse = $this->curl->exec();
    $this->responseMeta = $this->curl->getInfo();
  }
  
  protected function openConnection($method, $url) {
    $curl_options = $this->getCurlOptions($method, $url);
    $this->curl->init();
    $this->curl->setOptionArray($curl_options);
  }
  
  private function closeConnection() {
    $this->curl->close();
  }
  
  private function getCurlOptions($method, $url) {
    $options = $this->getInitialOptions();
    $this->setHeaderOptions($options);
    $this->setUrlOptions($options, $url);
    if ($method == 'GET') {
      $this->setGetParams($options, $url);
    } else {
      $this->setPostParams($options);
    }
    return $options;
  }
  
  private function setHeaderOptions(&$options) {
    if ($this->headers) {
      $options[CURLOPT_HTTPHEADER] = $this->formatHeaders();
    }
  }
  
  private function setUrlOptions(&$options, $url) {
    $options[CURLOPT_URL] = $url;
  }
  
  private function formatHeaders() {
    $formatted_headers = array();
    foreach ($this->headers as $key => $value) {
      $formatted_headers[] = "{$key}:{$value}";
    }
    return $formatted_headers;
  }
  
  private function setGetParams(&$options, $url) {
    $query_string = http_build_query($this->params);
    if ($this->params) {
      $url = "{$url}?{$query_string}";
    }
    $options[CURLOPT_URL] = $url;
  }
  
  private function setPostParams(&$options) {
    $options[CURLOPT_POSTFIELDS] = http_build_query($this->params);
    $options[CURLOPT_POST] = true;
  }
  
  protected function getInitialOptions() {
    return array(
      CURLOPT_CONNECTTIMEOUT => 10,
      CURLOPT_TIMEOUT        => 60,
      CURLOPT_RETURNTRANSFER => true, // Follow 301 redirects
      CURLOPT_HEADER         => true, // Enable header processing
      CURLOPT_SSL_VERIFYHOST => 2,
      CURLOPT_SSL_VERIFYPEER => true,
      CURLINFO_HEADER_OUT => true
    );
  }
  
}

require_once __DIR__.'/Curl.php';