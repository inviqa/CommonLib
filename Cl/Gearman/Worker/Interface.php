<?php 
/**
 * Gearman worker interface to be used for all gearman worker objects
 *
 * @package Cl
 **/
interface Cl_Gearman_Worker_Interface
{
	/**
	 * Create the object, instantiate the worker
	 *
	 * @param GearmanWorker $worker the Gearman Worker object to give work to
	 *
	 * @return void
	 **/
	public function init(GearmanWorker $worker);
	
	/**
	 * Start the worker
	 *
	 * @return void
	 **/
	public function start();
}