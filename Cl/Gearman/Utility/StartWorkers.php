<?php

/**
 * Start the gearman workers. Intended to be called from the command line
 *
 * @package Cl
 **/
class Cl_Gearman_Utility_StartWorkers
{
	const CLASS_SEPARATOR = '+';

	/**
	 * Start all the workers
	 *
	 * @param string $name    the name of the worker to start
	 * @param string $factory the name of the worker factory to use
	 *
	 * @return boolean true on success, false on failure
	 **/
	static public function start($name = 'All', $factory = 'default', $env = NULL)
	{
		if (NULL !== $env) {
			define('APPLICATION_ENV', $env);
		}

		$index = realpath(dirname(__FILE__) . '/../../../../public/index.php');
		require_once($index);

		$worker = new GearmanWorker;
		$worker->addServer();

		$factoryCall = 'Cl_Gearman_Worker_Factory_' . ucwords($factory);
		if ('all' === strtolower($name)) {
			$classes = call_user_func($factoryCall . '::getClasses');
		} else {
			$classes = explode(self::CLASS_SEPARATOR, $name);
		}
		foreach ($classes AS $class) {
			call_user_func($factoryCall . '::get', $class, $worker);
		}
		echo 'Worker started. Waiting for job...' . PHP_EOL;
		while ($worker->work());
	}
}

if (isset($argv) && count($argv) > 1) {
	array_shift($argv);
	call_user_func_array('Cl_Gearman_Utility_StartWorkers::start', $argv);
}