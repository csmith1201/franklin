<?php

namespace Franklin\test\config\type;

use
	Franklin\storage\CSV;

/**
 * @group Storage
 */
class CSVTest extends \PHPUnit_Framework_TestCase 
{
	protected $filename;
	
	public function setUp()
	{
		$this->filename = __DIR__.'/fixture/testfile.csv';
		$this->fixture = new CSV($this->filename);
	}
	
	public function tearDown()
	{
		if (file_exists($this->filename)) {
			unlink($this->filename);
		}
	}
	
	public function testStore()
	{
		$today = new \DateTime('24.12.2012 12:34');
		$this->fixture->store($today, 1000);
		$this->assertFileExists($this->filename);
		$this->assertStringEqualsFile($this->filename, '2012-12-24T12:34:00+01:00;1000'."\n");
	}
	
	protected function fillFixtureWithDummyData(Array $data)
	{
		foreach($data as $row) {
			$this->fixture->store($row[0], $row[1]);
		}
		return true;
	}
	
	public function testGetLatestValues()
	{
		// DUMMY DATA FILLUP
		$dateClassname = "\DateTime";
		$data = array(
			array(new $dateClassname('2012-12-24T11:00:00+01:00'), 50),
			array(new $dateClassname('2012-12-25T09:01:00+01:00'), 50),
			array(new $dateClassname('2012-12-26T12:34:00+01:00'), 50)
		);
		$this->fillFixtureWithDummyData($data);
		// CHECK IF we get any array shit back
		$lastData = $this->fixture->getLatestValues(2);
		$this->assertEquals(2, count($lastData));
		// check if sorted
		$this->assertEquals($data[0], $lastData[0]);
	}
}