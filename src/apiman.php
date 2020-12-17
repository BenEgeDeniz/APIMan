<?php

/**
 * APIMaster - A powerful API request manager class.
 * Developer: TimberLock
 * Developer Website: benegedeniz.com
 */
class APIMan
{
	private $endpoint;
	private $headers;
	private $data;
	private $request;
	private $sslConfig;
	private $ch;
	private $return;

	function __construct(string $endpoint)
	{
		$this->endpoint = $endpoint;
	}

	public function setSSLConfig(array $config)
	{
		$this->sslConfig = $config;
	}

	public function setHeaders(array $headers)
	{
		$this->headers = $headers;
	}

	public function setRequestType(string $type)
	{
		$supported = ["post", "get", "put", "delete"];

		if (!in_array(strtolower($type), $supported))
			throw new \Exception("Request type isn't supported (yet). Supported types: post, get, put, delete", 1);

		$this->request = $type;
	}

	public function setData($data)
	{
		$this->data = $data;
	}

	public function executeRequest()
	{
		if (gettype($this->sslConfig['SSL_VERIFYPEER']) != "boolean" || gettype($this->sslConfig['SSL_VERIFYPEER']) != "boolean")
			throw new \Exception("SSL configuration must be set correctly. Use setSSLConfig method to do that.", 1);

		$this->ch = curl_init();
		curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, true);

		if (!empty($this->headers))
			curl_setopt($this->ch, CURLOPT_HTTPHEADER, $this->headers);

		curl_setopt($this->ch, CURLOPT_HEADER, false);
		curl_setopt($this->ch, CURLOPT_SSL_VERIFYPEER, $this->sslConfig['SSL_VERIFYPEER']);

		if ($this->sslConfig['SSL_VERIFYHOST'] === true)
			curl_setopt($this->ch, CURLOPT_SSL_VERIFYHOST, 2);

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

		$this->return = curl_exec($this->ch);
		curl_close($this->ch);
	}

	public function getRawResponse()
	{
		return $this->return;
	}
}

?>