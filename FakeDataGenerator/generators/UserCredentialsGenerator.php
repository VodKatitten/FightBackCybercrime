<?php
include(__DIR__.'/EmailGenerator.php');
include(__DIR__.'/PasswordGenerator.php');

class UserCredentialsGenerator extends FakeGenerator
{
	private $params_default = [
		'pattern' => '{email}:{password}',
		'min_password_length' => 6,
		'hashed_password' => 0,
		'hash_function' => 'md5' // md5 or sha1
	];

	protected $data_autoload = false;
	private $fake_data = array();

	public function __construct()
	{
		parent::__construct();
		$this->params = $this->params_default;
	}

	public function generate($amount, $params = array())
	{
		$this->params = array_merge($this->params, $params);

		$emailGenerator = new EmailGenerator();
		$passwordGenerator = new PasswordGenerator();

		$emails = $emailGenerator->generate($amount, $params);

		$params['unique'] = false;
		$passwords = $passwordGenerator->generate($amount, $params);

		for($i=0; $i < $amount; $i++)
		{
			$password = $passwords[$i];
			
			if( (bool)$this->params['hashed_password'] )
			{
				// password should be hashed
				switch( $this->params['hash_function'] )
				{
					case 'sha1':
						$password = sha1($password);
						break;

					case 'md5':
					default: 
						$password = md5($password);
				}
			}

			$usercredential = str_replace('{email}', $emails[$i], $this->params['pattern']);
			$usercredential = str_replace('{password}', $password, $usercredential);
			$this->fake_data[] = $usercredential;
		}	

		return $this->fake_data;
	}

}