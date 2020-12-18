
# APIMan

A powerful API request manager class.


## Requirements

 - PHP 7 or upper.

## Installation

If you are using [Composer](https://getcomposer.org/), you can run the following command:

```
composer require benegedeniz/apiman
```

Or [download](https://github.com/BenEgeDeniz/APIMan/releases) APIMan directly and extract them to your web directory.

## Usage

```php
<?php

require __DIR__ . "/src/apiman.php"; // Requiring APIMan.

$api = new APIMan("https://api.benegedeniz.com/apitest"); // Creating a new APIMan handle with API endpoint URL.

$api->setHeaders([ // Setting headers to send (Optional, if you don't want to set headers, do not use this method.)
    "User-Agent: APIMan UG"
]);
$api->setSSLConfig([ // Setting SSL configuration (Required. Leave this as it is if you don't know what is this.)
    "SSL_VERIFYPEER" => true, // Required parameter. (If you get blank response from API, set this to false. If so, there is a good chance that you are using your own localhost.)
    "SSL_VERIFYHOST" => true // Required parameter.
]);
$api->setLogFile("log.txt"); // Logging file location. (Optional, if you don't want to use logging, don't use this method.)
$api->setProxy([ // Setting proxy tunnel (Optional, if you don't want to use proxy, don't use this method.)
	"proxy" => "204.101.61.82:4145", // Required, proxy IP. Format: ip:port.
	"proxyType" => "socks5", // Required, proxy type. Supported types: http, https, socks4 and socks5.
	"auth" => "username:password" // Optional, proxy authentication. Format: username:password.
]);
$api->setRequestType("post"); // Setting request type. Supported request types are: get, post, put and delete.
$api->setData(http_build_query(["testParam" => "Test value"])); // Setting data to send. You can send raw body too.
$api->executeRequest(); // Sending request.

echo $api->getRawResponse(); // Getting API response.

?>
```

## License

### This class is licensed with CC BY-NC-ND 4.0 (See:  [https://creativecommons.org/licenses/by-nc-nd/4.0/](https://creativecommons.org/licenses/by-nc-nd/4.0/))
