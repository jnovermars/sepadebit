<?php

/**
 *
 * PHP script SEPA export
 *
 * PHP script for export SEPA DEBIT file
 *
 * PHP version 5
 *
 *
 * LICENSE: Copyright (c) 2013 xleeuwx, The Netherlands
 * 
 * Permission is hereby granted, free of charge, to any person
 * obtaining a copy of this software and associated documentation
 * files (the "Software"), to deal in the Software without
 * restriction, including without limitation the rights to use,
 * copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the
 * Software is furnished to do so, subject to the following
 * conditions:
 *
 * The above copyright notice and this permission notice shall be
 * included in all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND,
 * EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES
 * OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND
 * NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT
 * HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY,
 * WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING
 * FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR
 * OTHER DEALINGS IN THE SOFTWARE. 
 *
 *
 * @category   	SepaExport
 * @package   	Database
 * @author    	xleeuwx <info@xleeuwx.nl> 
 * @copyright  	2009 - 2014 xleeuwx
 * @license   	http://www.opensource.org/licenses/mit-license.html  MIT License
 * @since      	File available since Release 1.0.0
 */ 

class database {	
	
	private $settings;
	private $dbhost;
	private $dbuser;
	private $dbpass;
	private $dbname;
	private $dbconn;
	
	/**
	 *
	 * Constructor
	 *
	 * @input none
	 * @return none
	 *
	 */
	 
	public function __construct() {
		## -- Load settings
			$this->fdbgetSettings();
			
		## -- Start Connection
			$this->fdbconnect();
			
	}
	
	/**
	 *
	 * Function get de default settings
	 *
	 * @input none
	 * @return none
	 *
	 */
	
	private function fdbgetSettings() {
		## -- Get settings class
			$settings = new Config();
		
		## -- Get settings
			$this->settings = $settings->fgetSettings("mysql");
		
		## -- Set settings
			$this->dbhost = $this->settings['dbhost'];
			$this->dbuser = $this->settings['dbuser'];
			$this->dbpass = $this->settings['dbpass'];
			$this->dbname = $this->settings['dbname'];
		
	}
	
	/**
	 *
	 * Function to create a new database connection 
	 *
	 * @input none
	 * @return none
	 *
	 */
	 
	private function fdbconnect() {
		## -- make mysql connection
		$this->dbconn = new mysqli($this->dbhost, $this->dbuser, $this->dbpass, $this->dbname);

		## -- Error handling
		if(mysqli_connect_errno()) {
			trigger_error("Error connection to database failed", E_USER_ERROR);
			exit();
		}

	}
	
	/**
	 *
	 * Function to close the database connection 
	 *
	 * @input none
	 * @return none
	 *
	 */
	 
	private function fdbclose() {
		## -- Mysql close function
			$varrslt = mysqli_close($this->dbconn);

		## -- Error handling
			if(!$varrslt) {
				trigger_error("fdbclose", E_USER_ERROR);
			}

	}
	
	/**
	 *
	 * Function to create a new database connection 
	 *
	 * @input none
	 * @return (mixed) -> the database link
	 *
	 */
	 
	
	public function fgetConnection() {
		return $this->dbconn;
	}

	/**
	 *
	 * Function to create a new database connection 
	 *
	 * @input (string) -> parse string to mysqli_real_escape_string and make it safe
	 * @return (string) -> the safe variabel $str
	 *
	 */
	 
	public function fclear($str) {
		## -- Only remove slashes if it's already been slashed by PHP
			$mq = ini_get('magic_quotes_gpc');
			if ($mq == true) {
				$str = stripslashes($str);
			}

		## -- Let MySQL remove nasty characters.
			$str = mysqli_real_escape_string($this->dbconn, $str);

		## -- Return value of str
			return $str;

	}
	
	/**
	 *
	 * Function to create a new database connection 
	 *
	 * @input none
	 * @return none
	 *
	 */
	
	public function __destruct() {
		## -- close db
			if(is_resource($this->dbconn)){
				$this->fdbclose();
				unset($this);
			}
	}
}

?>