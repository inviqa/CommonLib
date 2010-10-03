<?php

class TestModel extends Sso_Model_Base
{
	public static function factory($objectClass = 'Test', $id = null, $multiCurl = false)
	{
		return parent::factory($objectClass, $id, $multiCurl);
	}
	
	public function __initialise($id = NULL)
	{
		// Load an Sso_Client
		$this->_ssoClient = TestClient::getInstance();
		
		if ($id !== NULL) {
			// Create the Sso_Request object
			$this->_ssoRequest = new Sso_Request(array(
				'urlParams' => array($this->_objectClass, $id)
			));

			// Load the object
			$this->loadResult();
		}
	}
}