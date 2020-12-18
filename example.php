<?php

require __DIR__ . "/src/APIMan.php"; // Requiring APIMan.

$api = new APIMan("https://api.benegedeniz.com/apitest"); // Creating a new APIMan handle with API endpoint URL.

$api->setHeaders([ // Setting headers to send (Optional, if you dont want to set headers, do not use this method.)
	"User-Agent: APIMan UG"
]);
$api->setSSLConfig([ // Setting SSL configuration (Required. Leve this as it is if you don't know what is this.)
	"SSL_VERIFYPEER" => true,
	"SSL_VERIFYHOST" => true
]);
$api->setRequestType("post"); // Setting request type. Supported request types are: get, post, put, delete.
$api->setData(http_build_query(["testParam" => "Test value"])); // Setting data to send. You can sen raw body too.
$api->executeRequest(); // Sending request.

echo $api->getRawResponse(); // Gettin API response.

?>
