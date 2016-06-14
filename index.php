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
 * @package   	Sepa
 * @author    	xleeuwx <info@xleeuwx.nl> 
 * @copyright  	2009 - 2014 xleeuwx
 * @license   	http://www.opensource.org/licenses/mit-license.html  MIT License
 * @since      	File available since Release 1.0.0
 */ 

/**
 *
 * Includes required files
 * 
 * Get Config (Required for autoloader that is loading the classes)
 *
 * Get Members
 *
 */
    // Include Autoloader
        include_once("includes/autoLoader.inc.php");
	
    // Includes members file
        include_once("includes/getMembers.inc.php");

/**
 *
 * Start function get Members
 *
 * @return $members as array preset: array("memberID" => array("firstName", "insertion", "lastName", "IBAN", "BIC"))
 */	
$members = fgetMembers();

/**
 *
 * Create array of config for sepa Export 
 * @Input array();
 *
 */


$config = array("name" => "xleeuwx",
				"IBAN" => "NL24RABO0123456789",
				"BIC" => "RABONL2U",
				"batch" => true,
				"creditor_id" => "00000",
				"currency" => "EUR"				
				);

/**
 *
 * Start function get Members
 * @input $config as array: array("memberID" => array("firstName", "insertion", "lastName", "IBAN", "BIC"))
 * @input $members as array: array("memberID" => array("firstName", "insertion", "lastName", "IBAN", "BIC"))
 * @return $files as array;
 */	
$exportFile = new SepaExport($config, $members);

$zipFile = $exportFile->fstartExport();
	
?>