<?php 
/**
 * Gearman client object with base functionality
 *
 * @package Cl
 **/
class Cl_Gearman_Client_Base implements Cl_Gearman_Client_Interface
{
	/**
	 * The GearmanClient object from init
	 *
	 * @var GearmanClient
	 **/
	public $client;
	
	/**
	 * Create the object, instantiate the worker
	 *
	 * @param GearmanClient $client the Gearman Client object to request work
	 *
	 * @return void
	 **/
	public function init(GearmanClient $client)
	{
		$this->client = $client;
	}
	
	/**
	 * Send the job to the worker
	 *
	 * @param string $name the name of the job to call
	 * @param array  $data an array of data to send to the worker
	 *
	 * @return boolean true on success, false on failure
	 **/
	public function sendJob($name, $data)
	{
		return $this->client->do($name, serialize($data));
	}
	
	/**
	 * Send the background job to the worker
	 *
	 * @param string $name the name of the job to call
	 * @param array  $data an array of data to send to the worker
	 *
	 * @return boolean true on success, false on failure
	 **/
	public function sendBackgroundJob($name, $data)
	{
		return $this->client->doBackground($name, serialize($data));
	}
}