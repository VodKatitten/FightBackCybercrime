<?php

class EmailGenerator extends FakeGenerator
{

	private $pattern = [
		'{nickname}@{domain}',
		'{nickname}{number}@{domain}',
		'{firstname}{delimiter}{lastname}@{domain}',
		'{firstname}{number}@{domain}',
		'{lastname}{number}@{domain}',
		'{letter}{delimiter}{lastname}@{domain}',
		'{firstname}{delimiter}{letter}@{domain}',
		'{firstname}{random_number}@{domain}',
		'{lastname}{random_number}@{domain}',
		'{nickname}{random_number}@{domain}',
		'{firstname}{lastname}@{domain}',
	];

	private $fake_data = array();

	public function generate($amount, $params = array())
	{
		for($i=0; $i < $amount; $i++)
		{
			do {
				$email = $this->getNext();		
			}while( in_array($email, $this->fake_data) && (bool)$params['unique'] );
			
			$this->fake_data[] = $email;
		}
		
		return $this->fake_data;
	}

	public function getNext()
	{
		$pattern = mt_rand(0, count($this->pattern)-1);
		$pattern = $this->pattern[$pattern];

		preg_match_all('/\{([^\}]*)\}/', $pattern, $matches);

		if( !isset($matches[1]) || count($matches[1]) == 0 )
		{
			exit('EmailGenerator: Pattern '.$pattern.' not valid.'.PHP_EOL);
		}

		foreach($matches[1] as $part)
		{
			if( $part == 'random_number' )
			{
				$part_content = mt_rand(0, 9999);
			}
			else
			{
				$index = mt_rand(0,count($this->data[$part])-1); 
				$part_content = $this->data[$part][$index];
			}
		
			$pattern = str_replace('{'.$part.'}', $part_content, $pattern);
		}

		return $pattern;
	}
}