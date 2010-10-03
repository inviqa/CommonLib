<?php 

/**
 * Gearman worker object with base functionality
 *
 * @package Cl
 **/
class Cl_Gearman_Worker_Base implements Cl_Gearman_Worker_Interface
{
	/**
	 * The Gearman Worker object
	 *
	 * @var GearmanWorker
	 **/
	public $worker;
	
	/**
	 * Create the object, instantiate the worker
	 *
	 * @return void
	 **/
	public function init(GearmanWorker $worker)
	{
		$this->worker = $worker;
	}

	/**
	 * Start the worker working!
	 *
	 * @return void
	 **/
	public function start()
	{
		echo 'Worker started. Waiting for job...' . PHP_EOL;
		while ($this->worker->work());
	}
	
	/**
	 * Unserialise the data from the gearman workload
	 *
	 * @param string $serialisedData the serialised data from the gearman workload
	 *
	 * @return array the unserialised array of data
	 **/
	static public function getDataFromWorkload($serialisedData)
	{
		return unserialize($serialisedData);
	}
}