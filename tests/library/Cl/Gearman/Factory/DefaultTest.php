<?php

class Cl_Gearman_Factory_Default_Test extends PHPUnit_Framework_TestCase
{
	public function testGetNonExistentClass()
	{
		try {
			Cl_Gearman_Client_Factory_Default::get('nothing');
		} catch (Cl_Exception $e) { return; }
		
		$this->fail('Exception expected');
	}
}