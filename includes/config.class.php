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
 * @package   	Config
 * @author    	xleeuwx <info@xleeuwx.nl> 
 * @copyright  	2009 - 2014 xleeuwx
 * @license   	http://www.opensource.org/licenses/mit-license.html  MIT License
 * @since      	File available since Release 1.0.0
 */ 

class Config {
	private $config = array();

	/**
	 *
	 * Constructor
	 *
	 * @input none
	 * @return none
	 *
	 */
	 
	public function __construct() {
		## -- Get default Settings
			$this->config = $this->fgetDefaultConfig();
						
		## -- Set Mysql
			$this->fsetConfig("mysql", "dbhost", "localhost");
			$this->fsetConfig("mysql", "dbuser", "tutorials");
			$this->fsetConfig("mysql", "dbpass", "Secr*t");
			$this->fsetConfig("mysql", "dbname", "tutorials");
			
		## -- Set SEPA export
			$this->fsetConfig("exportSepa", "exportPath", $_SERVER['DOCUMENT_ROOT']."/tweakers/tutorial_2/export_output/");
			$this->fsetConfig("exportSepa", "exportZipFile", "SEPA_".date("H-i").".zip");
			
	}

	/**
	 *
	 * Function setting default settings
	 *
	 * @input none
	 * @return $config (array) -> return a array of arrays of with settings of mysql and exportSEPA
	 *
	 */

	private function fgetDefaultConfig() {
		$config = array();		
		
		## -- MySQL Settings
			$config['mysql'] = array('dbhost' => '', 'dbuser' => '','dbpass' => '', 'dbname' => '');
			$config['exportSepa'] = array('exportPath' => '', 'exportZipFile' => '');
				
		return $config;
	}

	/**
	 *
	 * Function setting default settings
	 *
	 * @input $group (string) -> the array group of settings
	 * @input $setting (string) -> the array key of setting 
	 * @input $value (string) -> the value of the key $setting
	 * @return none
	 *
	 */

	public function fsetConfig($group, $setting, $value) {
		## -- Set value of setting
			if(isset($this->config[$group])) {
				if(isset($this->config[$group][$setting])) {
					$this->config[$group][$setting] = $value;
				} else {
					trigger_error("No setting '$setting' found in group '$group'", E_USER_ERROR);
				}
			} else {
				trigger_error("No group setting '$group' found", E_USER_ERROR);
			}
	}
	
	/**
	 *
	 * Function get settings
	 *		
	 * @input $group (string) -> the array group of settings
	 * @input $setting (string)(not required) -> the array key of setting 
	 * @return (array) or (string) -> if isset $setting there will be returend a string, else a array	
	 *
	 */

	public function fgetSettings($group, $setting = false) {
		## -- Get settings of a group
			if(isset($this->config[$group])) {
				if($setting) {
					if(isset($this->config[$group][$setting])) {
						$this->config[$group][$setting] = $value;
					} else {
						trigger_error("No setting '$setting' found in group '$group'", E_USER_ERROR);
					}
				} else {
					return $this->config[$group];
				}
			} else {
				trigger_error("No group setting '$group' found", E_USER_ERROR);
			}
	}

}