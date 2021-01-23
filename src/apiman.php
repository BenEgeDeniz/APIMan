<?php

/**
 * APIMan - A powerful API request manager class.
 * Developer: TimberLock
 * Developer Website: benegedeniz.com
 */
class APIMan
{
	private $endpoint;
	private $headers;
	private $data;
	private $logHandle;
	private $request;
	private $proxyConfig;
	private $sslConfig;
	private $httpAuth;
	private $ch;
	private $return;

	function __construct(string $endpoint)
	{
		$this->endpoint = $endpoint;
	}

	/**
	 *
	 * setProxy
	 *
	 * This method will set proxy tunnel.
	 * 
	 * @param array $proxy This will be your proxy config.
	 *
	 * Example:
	 * Array
	 * (
     *		[proxy] => proxyip:port
     *		[proxyType] => {http, https, socks4, socks5}
     *		[auth] => username:password (optional)
	 *	)
	 *
	 * @return void
	 *
	 */

	public function setProxy(array $proxy)
	{
		$this->proxyConfig = $proxy;
	}

	/**
	 *
	 * setHeaders
	 *
	 * This method will set http headers that you want to send.
	 * 
	 * @param array $headers Your header array.
	 *
	 * Example:
	 * Array
	 * (
     *		[0] => Content-Type: application/json
     *		[1] => User-Agent: APIMan
	 *	)
	 *
	 * @return void
	 *
	 */

	public function setHeaders(array $headers)
	{
		$this->headers = $headers;
	}

	/**
	 *
	 * setRequestType
	 *
	 * This method will set your request type.
	 * 
	 * @param string $type Your request type. Can be: get, post, put and delete.
	 *
	 * @return void (If satisfied.)
	 * @return exception (If request type is invalid.)
	 *
	 */

	public function setRequestType(string $type)
	{
		$supported = ["post", "get", "put", "delete"];

		if (!in_array(strtolower($type), $supported))
			throw new \Exception("Request type isn't supported (yet). Supported types: post, get, put, delete", 1);

		unset($supported);

		$this->request = $type;
	}

	/**
	 *
	 * setHTTPAuth
	 *
	 * This method will set HTTP Authorization credentials.
	 * 
	 * @param string $credentials Your credentials. Format: username:password
	 *
	 * @return void
	 *
	 */

	public function setHTTPAuth(string $credentials)
	{
		$this->httpAuth = $credentials;
	}

	/**
	 *
	 * setData
	 *
	 * This method will set your request type.
	 * 
	 * @param mixed $data Your data to send. This can be a query or raw body. You can not use raw body with get!
	 *
	 * @return void
	 *
	 */

	public function setData($data)
	{
		$this->data = $data;
	}

	/**
	 *
	 * setSSLConfig
	 *
	 * This method will set SSL configuration.
	 * 
	 * @param array $config Your SSL configuration array. This is required.
	 *
	 * Example:
	 * Array
	 * (
     *		[SSL_VERIFYPEER] => (bool)
     *		[SSL_VERIFYHOST] => (bool)
	 *	)
	 *
	 * @return void
	 *
	 */

	public function setSSLConfig(array $config)
	{
		$this->sslConfig = $config;
	}

	/**
	 *
	 * setLogFile
	 *
	 * This method will set your cURL log file.
	 * 
	 * @param string $path Log file location.
	 *
	 * @return void
	 *
	 */

	public function setLogFile(string $path)
	{
		$this->logHandle = $path;
	}

	/**
	 *
	 * executeRequest
	 *
	 * This method will execute your request with your set parameters.
	 *
	 * @return void
	 *
	 */

	public function executeRequest()
	{
		if (gettype($this->sslConfig['SSL_VERIFYPEER']) != "boolean" || gettype($this->sslConfig['SSL_VERIFYPEER']) != "boolean")
			throw new \Exception("SSL configuration must be set correctly. Use setSSLConfig method to do that.", 1);

		$this->ch = curl_init();
		curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($this->ch, CURLOPT_HEADER, false);

		curl_setopt($this->ch, CURLOPT_SSL_VERIFYPEER, $this->sslConfig['SSL_VERIFYPEER']);

		if (!empty($this->httpAuth))
			curl_setopt($this->ch, CURLOPT_USERPWD, $this->httpAuth);  

		if ($this->sslConfig['SSL_VERIFYHOST'] === true)
			curl_setopt($this->ch, CURLOPT_SSL_VERIFYHOST, 2);

		if (isset($this->logHandle))
		{
			$this->logHandle = fopen($this->logHandle, "w");

			curl_setopt($this->ch, CURLOPT_VERBOSE, true);
			curl_setopt($this->ch, CURLOPT_STDERR, $this->logHandle);

			unset($this->logHandle);
		}

		unset($this->sslConfig);

		if (isset($this->proxyConfig['proxyType']) || isset($this->proxyConfig['proxy']))
		{
			$proxy = explode(":", $this->proxyConfig['proxy']);

			curl_setopt($this->ch, CURLOPT_PROXY, $proxy[0]);
			curl_setopt($this->ch, CURLOPT_PROXYPORT, $proxy[1]);

			if (strtolower($this->proxyConfig['proxyType']) == "http")
				curl_setopt($this->ch, CURLOPT_PROXYTYPE, CURLPROXY_HTTP);

			if (strtolower($this->proxyConfig['proxyType']) == "https")
				curl_setopt($this->ch, CURLOPT_PROXYTYPE, CURLPROXY_HTTPS);

			if (strtolower($this->proxyConfig['proxyType']) == "socks4")
				curl_setopt($this->ch, CURLOPT_PROXYTYPE, CURLPROXY_SOCKS4);

			if (strtolower($this->proxyConfig['proxyType']) == "socks5")
				curl_setopt($this->ch, CURLOPT_PROXYTYPE, CURLPROXY_SOCKS4);

			if (isset($this->proxyConfig['auth']))
				curl_setopt($this->ch, CURLOPT_PROXYUSERPWD, $this->proxyConfig['auth']);

			unset($this->proxyConfig);
		}

		if (!empty($this->headers))
			curl_setopt($this->ch, CURLOPT_HTTPHEADER, $this->headers);

		unset($this->headers);

		if (strtolower($this->request) == "post")
		{
			curl_setopt($this->ch, CURLOPT_URL, $this->endpoint);
			curl_setopt($this->ch, CURLOPT_CUSTOMREQUEST, "POST");

			if (!empty($this->data))
				curl_setopt($this->ch, CURLOPT_POSTFIELDS, $this->data);
		}

		if (strtolower($this->request) == "get")
		{
			curl_setopt($this->ch, CURLOPT_URL, $this->endpoint . "?" . $this->data);
			curl_setopt($this->ch, CURLOPT_CUSTOMREQUEST, "GET");
		}

		if (strtolower($this->request) == "put")
		{
			curl_setopt($this->ch, CURLOPT_URL, $this->endpoint);
			curl_setopt($this->ch, CURLOPT_CUSTOMREQUEST, "PUT");

			if (!empty($this->data))
				curl_setopt($this->ch, CURLOPT_POSTFIELDS, $this->data);
		}

		if (strtolower($this->request) == "delete")
		{
			curl_setopt($this->ch, CURLOPT_URL, $this->endpoint);
			curl_setopt($this->ch, CURLOPT_CUSTOMREQUEST, "DELETE");

			if (!empty($this->data))
				curl_setopt($this->ch, CURLOPT_POSTFIELDS, $this->data);
		}

		unset($this->data, $this->request, $this->endpoint);

		$this->return = curl_exec($this->ch);
		curl_close($this->ch);

		unset($this->ch);
	}

	/**
	 *
	 * getRawResponse
	 *
	 * This method will return the response from your API endpoint.
	 *
	 * @return Response from your API endpoint.
	 *
	 */

	public function getRawResponse()
	{
		return $this->return;

		unset($this->return);
	}
}

?>
