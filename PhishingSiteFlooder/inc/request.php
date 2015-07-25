<?php

/**
 A wrapper class for cURL in PHP.
 written by Tim (Eox).
 
 Example: 
	//Only a GET-Request
	$r = new Request('http://www.google.de', false);
	$r->run(false);
	
	//A POST-Request
	$r = new Request('http://www.google.de', true);
	$r->setOption('POSTFIELDS', 'test=true&work=false');
	$r->run(false);
	
**/

class Request
{
	//cURL 
	private $ch;
	
	public function __construct( $url, $post = false, $track_header = false, $ssl = false )
	{
		$this->ch = curl_init();
		$this->setopt(CURLOPT_URL, $url);	
		$this->setopt(CURLOPT_POST, $post);
		$this->setopt(CURLOPT_SSL_VERIFYPEER, (int)$ssl);
		$this->setopt(CURLOPT_SSL_VERIFYHOST, (int)$ssl);
		$this->setopt(CURLINFO_HEADER_OUT, $track_header);
	}
	
	
	public function newRequest( $url, $post = false )
	{
		$this->setopt(CURLOPT_URL, $url);	
		$this->setopt(CURLOPT_POST, $post);
	}
	
	//Optionen setzen
	public function setOption($key, $option)
	{
		switch($key)
		{
			case 'URL':
				$this->setopt(CURLOPT_URL, $option);
				break;
			case 'RETURN':
				$this->setopt(CURLOPT_RETURNTRANSFER, $option);
				break;
			case 'POST':
				$this->setopt(CURLOPT_POST, $option);
				break;
			case 'POSTFIELDS':
				$this->setopt(CURLOPT_POSTFIELDS, $option);
				break;
			case 'REFERER':
				$this->setopt(CURLOPT_REFERER, $option);
				break;
			case 'USER_AGENT':
				$this->setopt(CURLOPT_USERAGENT, $option);
				break;
			case 'TIMEOUT':
				$this->setopt(CURLOPT_TIMEOUT, $option);
				break;
			case 'COOKIES':
			case 'COOKIE':
				$this->setopt(CURLOPT_COOKIE, $option);
				break;
			case 'FOLLOWLOCATION':
				$this->setopt(CURLOPT_FOLLOWLOCATION, $option);
				break;
			case 'HEADER':
				$this->setopt(CURLOPT_HEADER, $option);
				break;
			case 'COOKIESESSION':
				$this->setopt(CURLOPT_COOKIESESSION, $option);
				break;
			default:
				$this->setopt($key, $option);
				break;
		}
	}
	
	private function setopt( $key, $option )
	{
		curl_setopt($this->ch, $key, $option);
	}
	
	//Einen Proxy-Server einstellen
	public function setProxy( $ip, $port )
	{
		curl_setopt($this->ch, CURLOPT_PROXY, $ip);   
		curl_setopt($this->ch, CURLOPT_PROXYPORT, $port);
	}
	
	//Request senden
	public function run( $return = false )
	{
		
		$this->setOption('RETURN', $return);
		
		//Return or echo?
		if( $return )
		{
			return curl_exec($this->ch);
		}
		else
		{
			curl_exec($this->ch);
		}
	}
	
	//Gibt die Fehlernummer (0 = kein Fehler)
	public function errno()
	{
		return curl_errno($this->ch);
	}
	
	//Liefert den HTTPCode des letzten Requests
	public function getHTTPCode()
	{
		return curl_getinfo($this->ch, CURLINFO_HTTP_CODE);
	}
	
	/* 
		Helper - Functions which make things easier
	*/
	public function useBrowser( $name )
	{
		switch( $name )
		{
			case 'IE':
			case 'Internet Explorer';
				$this->setOption('USER_AGENT', 'Mozilla/5.0 (compatible; MSIE 9.0; Windows NT 6.1; WOW64; Trident/5.0)');
				break;
			
			case 'Chrome';
				$this->setOption('USER_AGENT', 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.11 (KHTML, like Gecko) Chrome/23.0.1271.95 Safari/537.11');
				break;
			
			case 'Firefox';
				$this->setOption('USER_AGENT', 'Mozilla/6.0 (Windows NT 6.2; WOW64; rv:16.0.1) Gecko/20121011 Firefox/16.0.1');
				break;
			
			default: 
				$this->setOption('USER_AGENT', $name);
				break;
		}
	}
	
	public function postData( $postfields )
	{		
		$this->setOption('POSTFIELDS', $postfields);
	}

	public function getRequestHeader()
	{
		return curl_getinfo($this->ch, CURLINFO_HEADER_OUT);
	}

	public function getInfo($opt)
	{
		return curl_getinfo($this->ch, $opt);
	}
}
