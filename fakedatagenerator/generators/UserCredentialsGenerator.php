<?php
include(__DIR__.'/EmailGenerator.php');
include(__DIR__.'/PasswordGenerator.php');

class UserCredentialsGenerator extends FakeGenerator
{
	private $params_default = [
		'pattern' => '{email}:{password}',
		'min_password_length' => 6
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
			$usercredential = str_replace('{email}', $emails[$i], $this->params['pattern']);
			$usercredential = str_replace('{password}', $passwords[$i], $usercredential);
			$this->fake_data[] = $usercredential;
		}	

		return $this->fake_data;
	}

}