<?php 
/**
 * Base class for creating and serving Csv files
 *
 * @package Cw
 **/
class Cl_File_Csv_Base
{
	/**
	 * The header line for the file
	 *
	 * @var string
	 **/
	protected $_header;
	
	/**
	 * The data for the file
	 *
	 * @var array
	 **/
	protected $_data;
	
	/**
	 * Set the header data, checking the format
	 *
	 * @param string $data a Csv string
	 *
	 * @return void
	 **/
	public function setHeader($data)
	{
		if ($this->isValid($data)) {
			$this->_header = $data;
			return true;
		}
		throw new Cl_Exception("Invalid format: Must be Csv Data", 1);
	}
	
	/**
	 * Set the data, checking the format
	 *
	 * @param array $data an array of Csv strings
	 *
	 * @return void
	 **/
	public function setData($data)
	{
		if (is_array($data)) {
			foreach ($data as $key => $datum) {
				if (!$this->isValid($datum)) {
					throw new Cl_Exception("Invalid format: Must be Csv Data", 1);
				}
			}
			$this->_data = $data;
			return true;
		}
		throw new Cl_Exception("Data must be an array", 1);
		
	}
	
	/**
	 * Check if the data is valid Csv format
	 *
	 * @param string $data a Csv string
	 *
	 * @return boolean true on success, false on failure
	 **/
	protected function isValid($data)
	{
		return preg_match('/^[a-zA-Z0-9_, \r\n\t]+$/', $data);
	}
	
	/**
	 * Serve the file to the browser
	 *
	 * @param string $fileName the name of the file to serve
	 *
	 * @return void
	 **/
	public function serveFile($fileName)
	{
		array_unshift($this->_data, $this->_header);
		header('Content-type: application/csv');
		header('Content-Disposition: attachment; filename=' . $fileName . '.csv');
		header('Pragma: no-cache');
		header('Expires: 0');
		echo implode("\n", $this->_data);
	}
}