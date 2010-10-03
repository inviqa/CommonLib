<?php

/**
 * Default factory class for instantiating Cw_Gearman_Worker_* objects
 *
 * @package Cl
 **/
class Cl_Gearman_Worker_Factory_Default
{
	/**
	 * An array of classed which can be instantiated
	 * The key is the last word of the object name
	 * The value determines whether it should be a new
	 * object (multiple) or a singleton (single)
	 *
	 * @var array
	 **/
	protected static $_classes = array(
	);

	public static function getClasses()
	{
		return array_keys(self::$_classes);
	}
	
	/**
	 * An array of objects which are already instantiated
	 *
	 * @var string
	 **/
	protected static $_objects = array();
	
	/**
	 * Get an instance of the specified object
	 *
	 * @return object
	 **/
	public static function get($name, $worker = NULL)
	{
		if (array_key_exists($name, self::$_classes)) {
			if (self::$_classes[$name] == 'single') {
				if (array_key_exists($name, self::$_objects)) {
					return self::$_objects[$name];
				} else {
					$object = self::_getNewObject($name, $worker);
					if (is_object($object)) {
						self::$_objects[$name] = $object;
						return self::$_objects[$name];
					}
				}
			} elseif (self::$_classes[$name] == 'multiple') {
				$object = self::_getNewObject($name, $worker);
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
	 * @author work
	 **/
	protected static function _getNewObject($name, $worker = NULL)
	{
		$className = 'Cl_Gearman_Worker_' . $name;
		if (class_exists($className)) {
			$object = new $className;
			
			if (NULL === $worker) {
				$worker = new GearmanWorker;
				$worker->addServer();
			}
			$object->init($worker);
			return $object;
		}
		return false;
	}
}