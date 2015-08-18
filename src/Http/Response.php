<?php

namespace PeanutLabs\SampleApiSDK\Http;

class Response {
  
  const HTTP_STATUS = 'http_code';
  
  private $rawResponse;
  private $responseMeta;
  private $headers;
  private $responseBody;
  
  public function setRawResponse($raw_response) {
    $this->rawResponse = $raw_response;
    return $this;
  }
  
  public function setResponseMeta($response_meta) {
    $this->responseMeta = $response_meta;
    return $this;
  }
  
  public function getHttpStatusCode() {
    return $this->responseMeta[static::HTTP_STATUS];
  }
  
  public function getResponseBody() {
    return $this->responseBody;
  }
  
  public function formatResponse() {
    $header_size = $this->getHeaderSize();
    $this->headers = $this->headersToArray(trim(mb_substr($this->rawResponse, 0, $header_size)));
    $this->responseBody = json_decode(trim(mb_substr($this->rawResponse, $header_size)), 1);
  }
  
  public function isSuccess() {
    return $this->responseBody['resultCode'] == 1;
  }
  
  public function getErrorMessage() {
    return $this->responseBody['resultError'];
  }
    
  private function getHeaderSize() {
    return $this->responseMeta['header_size'];
  }
  
  protected function headersToArray($raw_headers) {
    $headers = array();
    // Normalize line breaks
    $raw_headers = str_replace("\r\n", "\n", $raw_headers);
    // There will be multiple headers if a 301 was followed
    // or a proxy was followed, etc
    $header_collection = explode("\n\n", trim($raw_headers));
    // We just want the last response (at the end)
    $raw_headers = array_pop($header_collection);
    $heade_components = explode("\n", $raw_headers);
    foreach ($heade_components as $line) {
      if (strpos($line, ': ') === false) {
        $headers['http_code'] = $line;
      } else {
        list ($key, $value) = explode(': ', $line);
        $headers[$key] = $value;
      }
    }
    return $headers;
  }
  
}

