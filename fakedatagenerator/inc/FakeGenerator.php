<?php

abstract class FakeGenerator {
	
	protected $data_autoload = true;

	public function __construct()
	{
		if( $this->data_autoload === true )
		{
			$data_file = DATA_DIR.get_class($this).'.data';
		
			if( !file_exists($data_file) )
			{
				exit('File '.$data_file.' doesnt exist!');
			}

			$this->data = json_decode(file_get_contents($data_file), true);

			if( json_last_error() > 0 )
			{
				exit('File '.$data_file.' caused an JSON-Error ('.json_last_error().').'.PHP_EOL);
			}
		}
	}

	abstract public function generate($amount, $params = array());

}