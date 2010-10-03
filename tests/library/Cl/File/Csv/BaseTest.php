<?php

class Cl_File_Csv_Base_Test extends PHPUnit_Framework_TestCase {
	
	public function setUp()
	{
		$this->_subject = new Cl_File_Csv_Base;
	}
	
	public function tearDown()
	{
		$this->_subject = null;
	}
	
	/**
	 * Given the supplied data is not an array
	 * The model should throw an exception
	 */
	public function testSetDataNotArray()
	{
		$data = 'dodgy data';
		
		try {
			$this->_subject->setData($data);
		} catch (Cl_Exception $e) {
			return;
		}
		
		$this->fail('Exception expected');
	}
	
	/**
	 * Given the supplied data is an array
	 * And the array contains values that include non-alphanumeric or line-end or tab characters
	 * The model should throw an error
	 */
	public function testSetDataNotCsvFormat()
	{
		$data = array('h@ck');
		
		try {
			$this->_subject->setData($data);
		} catch (Cl_Exception $e) {
			return;
		}
		
		$this->fail('Exception expected');
	}
	
	/**
	 * Given the supplied data is an array
	 * And the array contains values that include non-alphanumeric or line-end or tab characters
	 * The model should throw an error
	 */
	public function testSetHeaderNotCsvFormat()
	{
		$data = 'h@ck';
		
		try {
			$this->_subject->setHeader($data);
		} catch (Cl_Exception $e) {
			return;
		}
		
		$this->fail('Exception expected');
	}
}