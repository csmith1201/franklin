<?php

namespace Franklin\test\GitHub\UserInfo;

use
	Franklin\test\Test,
	Franklin\network\CURL
	;

class UserInfo extends Test
{
	public $name = 'GitHub User-Info';
	
	public $description = 'Information about a github user';
	
	public function run()
	{
		$this->beforeRun();
		$url = 'https://api.github.com/users/'.$this->config->username;
		$CURL = new CURL();
		$result = $CURL->get($url);
		if ($result && $data = json_decode($result, true)) {
			return (int) $data[$this->config->key];
		}
		return 0;
	}
}