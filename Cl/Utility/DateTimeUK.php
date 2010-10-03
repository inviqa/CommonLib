<?php

/**
 * Allows use of PHP DateTime class with UK formatted date structure
 *
 * @package Cl_Utility
 **/
class Cl_Utility_DateTimeUK extends DateTime
{
	/**
	 * @var string Default date format
	 **/
	const DEFAULT_FORMAT = 'd/m/Y H:i';

	/**
	 * Contructor
	 *
	 * @param string $time String in a format accepted by strtotime(). Where m/d/y format is normally used, this takes d/m/y format dates.
	 * @param DateTimeZone $timezone Time zone of the time.
	 * @return void
	 * @throws Exception in case of error (date format is invalid)
	 **/
	public function __construct($time = 'now', $timezone = NULL)
	{
		if (preg_match('#\d{4}/\d{1,2}/\d{1,2}#', $time)) {
			// all ok
		} elseif (preg_match('#^(.*?)(\d{1,2})/(\d{1,2})/(\d+)(.*?)$#', $time, $matches)) {
			// switch m/d/y format into d/m/y
			$time = $matches[1] . $matches[3] . '/' . $matches[2] . '/' . $matches[4] . $matches[5];
		}

		// if timezone is null, do not pass to constructor since throws exception if you pass NULL
		if (NULL === $timezone) {
			parent::__construct($time);
		} else {
			parent::__construct($time, $timezone);
		}
	}

	/**
	 * ukstrtotime - equivalent of strtotime, but works on uk date formats (d/m/y) as opposed to m/d/y formats.
	 *
	 * @param string $time The string to parse.
	 * @param int $now The timestamp which is used as a base for the calculation of relative dates.
	 * @return int
	 * @see function strtotime
	 **/
	public static function ukstrtotime($time, $now = NULL)
	{
		// switch m/d/y format into d/m/y
		if (preg_match('#^(.*?)(\d{1,2})/(\d{1,2})/(\d+)(.*?)$#', $time, $matches)) {
			$time = $matches[1] . $matches[3] . '/' . $matches[2] . '/' . $matches[4] . $matches[5];
		}
		return strtotime($time, $now);
	}

	/**
	 * Returns date formatted according to given format
	 *
	 * @param string $format Format accepted by date().
	 * @return string
	 **/
	public function format($format = self::DEFAULT_FORMAT)
	{
		return parent::format($format);
	}
}