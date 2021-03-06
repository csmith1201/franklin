<?php

namespace Franklin;

class Franklin
{
	protected $groups = array();
	
	protected $config;
	
	public function report()
	{
		\Franklin\view\View::$baseDir = FRANKLIN_ROOT.'/view/';
		$action = 'report';
		if (isset($_GET['action'])) {
			$action = $_GET['action'];
		}
		$layout = new \Franklin\view\View('layout/default.html');
		$layout['groups'] = $this->groups;
		switch($action) {
			case 'report':
			default:
				$view = new \Franklin\view\View('report.html');
				break;
			case 'test':
				$view = new \Franklin\view\View('test.html');
				$view['Test'] = $this->findTestById($_GET['id']);
				if (isset($_GET['days'])) {
					$view['days'] = (int) $_GET['days'];
				} else {
					$view['days'] = 30;
				}
				$layout['Test'] = $view['Test'];
				break;
			case 'compare':
				$view = new \Franklin\view\View('compare.html');
				foreach($_GET['ids'] as $id) {
					if (!($test = $this->findTestById($_GET['id']))) continue;
					$tests[] = $test;
				}
				$view['Tests'] = $tests;
				break;
		}
		$view['action'] = $action;
		$view['groups'] = $this->groups;
		$view['config'] = $this->config;
		$view['franklin'] = $this;
		$layout['content'] = $view;
		return $layout;
	}
	
	public function findTestById($id)
	{
		foreach($this->groups as $Group) {
			foreach($Group as $Test) {
				if ($Test->uniqueId() == $id) {
					return $Test;
				}
			}
		}
		return false;
	}
	
	public function cron()
	{
		$argv = $_SERVER['argv'];
		$options = array();
		foreach($argv as $value) {
			if (preg_match('@--([^=]+)=(.+)@', $value, $found)) {
				$options[$found[1]] = $found[2];
			}
		}
		$this->runTests($options);
	}
	
	public function loadConfig($filename)
	{
		require $filename;
		$this->config = new Config($config);
		foreach($this->config['groups'] as $groupConfig) {
			$this->groups[] = new Group($groupConfig['name'], $groupConfig);
		}
		// set the timezone from the config
		if (!empty($this->config->timezone)) {
			date_default_timezone_set($this->config->timezone);
		}
		return $this;
	}
	
	public function storage(\Franklin\test\Test $Test)
	{
		$filename =
			FRANKLIN_ROOT.'/data/'.$Test->uniqueId().'.csv';
		;
		$Storage = new \Franklin\storage\CSV($filename);
		return $Storage;
	}
	
	protected function runTests(Array $filter = array())
	{
		$startTimestamp = time();
		$counters = array(
			'tests' => 0,
			'runs' => 0,
		);
		foreach($this->groups as $TestGroup) {
			if (isset($filter['group']) && (string) $TestGroup !== $filter['group']) {
				continue;
			}
			printf('Processing group: %s'.PHP_EOL, $TestGroup);
			foreach($TestGroup as $Test) {			
				$counters['tests']++;
				if (empty($Test->lastTestTimestamp) || $Test->lastTestTimestamp + $Test->interval < time()) {
					$result = $Test->run();
					$this->storage($Test)->store(new \DateTime(), $result);
					$counters['runs']++;
					printf('%s: %f'.PHP_EOL, $Test->name, $result);
				}
			}
		}
		printf('%s tests checked, %s done, %s skipped in %d seconds', $counters['tests'], $counters['runs'], $counters['tests'] - $counters['runs'], time() - $startTimestamp);
	}
}