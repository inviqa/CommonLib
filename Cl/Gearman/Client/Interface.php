<?php 
/**
 * Gearman client interface to be used for all gearman client objects
 *
 * @package Cl
 **/
interface Cl_Gearman_Client_Interface
{
	/**
	 * Create the object, instantiate the worker
	 *
	 * @param GearmanClient $client the Gearman Client object to request work
	 *
	 * @return void
	 **/
	public function init(GearmanClient $client);
}