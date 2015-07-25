<?php

class PasswordGenerator extends FakeGenerator
{

	private $params_default = array(
		'min_password_length' => 6,
		'hashed_password' => 0,
		'hash_function' => 'md5' // md5 or sha1
	);

	private $pattern = array(
		'{10k_passwords}{random_number}',
		'{firstname}{random_number}',
		'{lastname}{random_number}',
		'{nickname}{random_number}',
		'{firstname}{delimiter}{lastname}'
	);

	private $fake_data = array();

	private $count_10kpasswords = null;
	// how often the script will try to reach the min_password_length
	// before adding random numbers
	private $trys_to_find_password = 5;

	
	// chance for one of the 10k most used passwords
	private $most_used_password_chance = 50; 

	// chance for generate a new password based on the given pattern
	private $pattern_password_chance = 40; 

	// chance for a complete random password like 'g7Ag83zgh6r'
	private $random_password_chance = 8; 

	// chance for a real secure password like 'CV4mg.hT*Wh85A9?d0'
	private $secure_password_chance = 2; 

	private $password_chance = '';
	private $password_chance_length = 0;

	public function __construct()
	{
		parent::__construct();

		$this->params = $this->params_default;

		$this->password_chance .= str_repeat('M', $this->most_used_password_chance);
		$this->password_chance .= str_repeat('P', $this->pattern_password_chance);
		$this->password_chance .= str_repeat('R', $this->random_password_chance);
		$this->password_chance .= str_repeat('S', $this->secure_password_chance);
		$this->password_chance = str_shuffle($this->password_chance);
		$this->password_chance_length = mb_strlen($this->password_chance);
	}

	public function generate($amount, $params = array())
	{
		$this->params = array_merge($this->params, $params);
		
		for($i=0; $i < $amount; $i++)
		{
			do {
				$password = $this->getNext();	

				if( (bool)$this->params['hashed_password'] )
				{
					// password should be hashed
					switch($this->params['hash_function'])
					{
						case 'sha1':
							$password = sha1($password);
							break;

						case 'md5':
						default: 
							$password = md5($password);
							break;
					}
				}	
			}while( in_array($password, $this->fake_data) && (bool)$params['unique'] );
			
			$this->fake_data[] = $password;
		}

		return $this->fake_data;
	}


	public function getNext()
	{
		$type = $this->password_chance[mt_rand(0, $this->password_chance_length-1)];
		
		switch($type)
		{
			case 'M':
				return $this->getPopularPassword();

			case 'P':
				return $this->getPatternPasswort();

			case 'R':
				return $this->getRandomPassword();

			case 'S':
				return $this->getSecurePassword();

			default:
				return $this->getPopularPassword(); // should never happen!
		}

	}

	private function getPopularPassword()
	{
		if( $this->count_10kpasswords == null )
		{
			$this->count_10kpasswords = count($this->data['10k_passwords']);
		}

		$tries = 0;
		do {
			$index = mt_rand(0, $this->count_10kpasswords-1);
		}while(mb_strlen($this->data['10k_passwords'][$index]) < $this->params['min_password_length'] 
				&& ++$tries < $this->trys_to_find_password );

		// password with min_password_length found
		if( mb_strlen($this->data['10k_passwords'][$index]) >= $this->params['min_password_length'] )
		{
			return $this->data['10k_passwords'][$index];
		}

		$password = $this->data['10k_passwords'][$index];
		while( mb_strlen($password) < $this->params['min_password_length'] )
		{
			$password .= mt_rand(0, 9);
		}

		return $password;
	}

	private function getRandomPassword()
	{
		$chars = str_shuffle('abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789');
		$chars_count = mb_strlen($chars);
		$length = mt_rand($this->params['min_password_length'], $this->params['min_password_length']+3);
		$password = '';

		do {
			$password .= $chars[mt_rand(0, $chars_count-1)];
		}while(mb_strlen($password) < $length);

		return $password;
	}

	private function getSecurePassword()
	{
		$chars = str_shuffle('abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789*=?!+_-:.,;()/<>$&@#%');
		$chars_count = mb_strlen($chars);
		$length = mt_rand(10, 15);
		$password = '';

		do {
			$password .= $chars[mt_rand(0, $chars_count-1)];
		}while(mb_strlen($password) < $length);

		return $password;
	}

	private function getPatternPasswort()
	{
		$tries = 0;
		do {
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
		}while(mb_strlen($pattern) < $this->params['min_password_length']
			   && ++$tries < $this->trys_to_find_password );

		if( mb_strlen($pattern) >= $this->params['min_password_length'] )
		{
			return $pattern;
		}

		do {
			$pattern .= mt_rand(0,9);
		}while(mb_strlen($pattern) < $this->params['min_password_length']);

		return $pattern;
	}
}