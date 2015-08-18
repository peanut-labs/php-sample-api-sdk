# php-sample-api-sdk

#### Requirements in composer.json file

```json
{
  "require": {
      "peanut-labs/php-sample-api-sdk": "dev-master"
  },
  "repositories": [
      {
          "url": "git@github.com:peanut-labs/php-sample-api-sdk.git",
          "type": "git"
      }
  ]
}
```

#### bootstraping
In order to use the SDK you need to make sure its classes are autoloaded, composer would normally take care of this, if for some reason this doesn't happen just make sure the SDK's autoload.php file is required at some point.

Then you need to instantiate a Client object

SDK has its own namespace and the constructor for a Client object has three parameters, $advertiser_id, $security_key and $host.

Example:

```php
use PeanutLabs\SampleApiSDK\Client;

$client = new Client($advertiser_id, $security_key, $host);

```

$advertiser_id is a unique identifier provided to you by Peanut Labs.
$security_key is a secret key provided to you by Peanut Labs.
$host is the base URI of the Sample API.

#### Usage

There is only one method sendRequest, which will return a Response object.

Method sendRequest will expect three parammeters, $method, $path, $params.

Example calls using the bootstrapped client object globally:

```php
global $client;

$response = $client->sendRequest($method, $path, $params);

```

$method is HTTP method, like GET and POST.
$path is resource, like '/projects/test001'.
$params is all the request params as a php array.

#### Parsing Response

There are two methods getResponseBody and getHttpStatusCode.

Example:

```php

$response_body = $response->getResponseBody();
$http_status_code = $response->getHttpStatusCode();

```

