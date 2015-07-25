<?php 
include('./inc/FakeGenerator.php');

define('GENERATORS_DIR', realpath('./generators').'/');
define('DATA_DIR', realpath('./data').'/');

$default = array(
	'generator' => 'EmailGenerator',
	'amount' => 1,
	'unique' => 1,
	'output' => 'dump.txt'
);

$params = $default;
for($i=1; $i < count($argv); $i++)
{
	preg_match('/--([^=]*)=([^\s]*)/i', $argv[$i], $matches);
	$params[$matches[1]] = $matches[2];
}

if( !file_exists(GENERATORS_DIR.$params['generator'].'.php') )
{
	// generator doesnt exist
	exit('Generator '.$params['generator'].' doesnt exist!'.PHP_EOL);
}

include(GENERATORS_DIR.$params['generator'].'.php');

if( !class_exists($params['generator']) )
{
	// generator class doesnt exist
	exit('Class '.$params['generator'].' doesnt exist!'.PHP_EOL);
}

$generator = new $params['generator']();

if( !($generator instanceof FakeGenerator) )
{
	// generator class doesnt implement the IFakeGenerator interface
	exit('Class '.$params['generator'].' doesnt implements the IFakeGenerator interface!'.PHP_EOL);	
}

// create output file
file_put_contents(
	$params['output'], 
	implode(PHP_EOL, $generator->generate($params['amount'], $params))
);

echo 'Done!', PHP_EOL;