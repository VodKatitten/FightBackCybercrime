<?php
include(dirname(__FILE__).'/inc/request.php');

define('CONFIG_DIR', dirname(__FILE__).'/config/');

$default = array(
	'delimiter' => ':', // {email}:{password}
	'use' => 'dump.txt',
	'use_proxy' => 0,
	'target' => '',
	'http_post' => 1,
	'browser' => 'chrome',
	'timeout' => 10
);

$params = $default;
for($i=1; $i < count($argv); $i++)
{
	preg_match('/--([^=]*)=(.*)/i', $argv[$i], $matches);

	if( isset($matches[1]) && isset($matches[2]) )
	{
		$params[$matches[1]] = $matches[2];
	}
}

// prepare request 
$post_data = json_decode(file_get_contents(CONFIG_DIR.'post_data.json'), true);
if( json_last_error() > 0 ){ exit('JSON Error in post_data.json!'); }

if( (bool)$params['use_proxy'] )
{
	$proxy_list = json_decode(file_get_contents(CONFIG_DIR.'proxy_list.json'), true);
	if( json_last_error() > 0 ){ exit('JSON Error in proxy_list.json!'); }
}

$request = new Request($params['target'], (bool)$params['http_post']);
$request->useBrowser($params['browser']);
$request->setOption('TIMEOUT', $params['timeout']);

// does the file with fake user credentials exist?
if( !file_exists($params['use']) )
{
	exit('File '.$params['use'].' doesnt exist!');
}

$requests = 0;
$requests_200 = 0;
$handle = fopen($params['use'], 'r');

while( $line = trim(fgets($handle)) )
{
	if( (bool)$params['use_proxy'] )
	{
		if( count($proxy_list) == 0 )
		{
			exit('No proxy in proxy_list.json');
		}

		$proxy = $proxy_list[mt_rand(0, count($proxy_list)-1)];
		$request->setProxy($proxy['ip'], $proxy['port']);
	}

	$user = explode($params['delimiter'], $line);
	$data = $post_data;

	foreach($data as $key=>$value)
	{
		$data[$key] = str_replace(['{email}','{password}'], $user, $value);
	}

	$request->postData($data);
	$request->run(true);

	// stats
	$requests++;
	if( $request->getHTTPCode() == 200 ){ $requests_200++; }

	echo $requests, ' HTTP-Status: '.$request->getHTTPCode(), ' Email: ',$user[0],' Password: ',$user[1],PHP_EOL;
	
	if( (bool)$params['use_proxy'] )
	{
		echo 'Used Proxy: ', $proxy['ip'], ':', $proxy['port'], PHP_EOL;
	}
}

echo 'Done! ', $requests, ' requests send. ', $requests_200, ' requests with HTTP Code 200.';