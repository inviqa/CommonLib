<?php 
/**
 * Default factory class for instantiating Cl_Gearman_Client_* objects
 *
 * @package Cl
 **/
class Cl_Gearman_Client_Factory_Default
{
	/**
	 * An array of classed which can be instantiated
	 * The key is the last word of the object name
	 * The value determines whether it should be a new
	 * object (multiple) or a singleton (single)
	 *
	 * @var array
	 **/
	static $classes = array(
		);
	
	/**
	 * An array of objects which are already instantiated
	 *
	 * @var string
	 **/
	static $objects = array();
	
	/**
	 * Get an instance of the specified object
	 *
	 * @return object
	 **/
	static function get($name)
	{
		if (array_key_exists($name, self::$classes)) {
			if (self::$classes[$name] == 'single') {
				if (array_key_exists($name, self::$objects)) {
					return self::$objects[$name];
				} else {
					$object = self::getNewObject($name);
					if (is_object($object)) {
						self::$objects[$name] = $object;
						return self::$objects[$name];
					}
				}
			} elseif (self::$classes[$name] == 'multiple') {
				$object = self::getNewObject($name);
				if (is_object($object)) {
					return $object;
				}
			}
		}
		throw new Cl_Exception("This object does not exist.", 1);
	}
	
	/**
	 * Define the implementing classes available from this factory.
	 *
	 * @param Zend_Config $config 
	 * @return void
	 */
	static function addClasses(Zend_Config $config) {
		self::$classes = $config->toArray();
	}
	
	/**
	 * Logic for making a new object
	 *
	 * @return void
	 **/
	static function getNewObject($name)
	{
		$className = 'Cl_Gearman_Client_' . $name;
		if (class_exists($className)) {
			$object = new $className;
			$client = new GearmanClient;
			$client->addServer();
			$object->init($client);
			return $object;
		}
		return false;
	}
}