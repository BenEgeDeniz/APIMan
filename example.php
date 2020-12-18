<?php

require __DIR__ . "/src/APIMan.php"; // Requiring APIMan.

$api = new APIMan("https://api.benegedeniz.com/myip"); // Creating a new APIMan handle with API endpoint URL.

$api->setHeaders([ // Setting headers to send (Optional, if you don't want to set headers, do not use this method.)
    "User-Agent: APIMan UG"
]);
$api->setSSLConfig([ // Setting SSL configuration (Required. Leave this as it is if you don't know what is this.)
    "SSL_VERIFYPEER" => false, // Required parameter.
    "SSL_VERIFYHOST" => false // Required parameter.
]);
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
