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
 * LICENSE: Copyright (c) 2013 Congressus, The Netherlands
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
 * @package   	Member_info
 * @author    	xleeuwx <info@xleeuwx.nl> 
 * @copyright  	2009 - 2014 xleeuwx
 * @license   	http://www.opensource.org/licenses/mit-license.html  MIT License
 * @since      	File available since Release 1.0.0
 */ 

/**
 *
 * Get Members from database
 * @input 
 *
 */	

function fsetMembers($members) {

	$db = new database();
	$conn = $db->fgetConnection();
	
	
	// Get clients	
		foreach($members as $memberID => $member_info) {
			$generate_iban = getIban();
			$iban = $generate_iban['iban'];
			$bic = $generate_iban['BIC'];
			$city = $generate_iban['bank_city'];
			$sql = "UPDATE `member_info` SET `bank_account` = '$iban', `bank_city` = '$city', `bank_bic` = '$bic' WHERE `memberID` = '$memberID'";
			
			$query = mysqli_query($conn, $sql);
		}
}

function getIban() {

	$db = new database();
	$conn = $db->fgetConnection();
	
	// Get Bi codes
		$sql = "SELECT `BI`, `BIC`, `bank_city` FROM `bic_codes` WHERE `bank_country` = 'NL'";
					
		$query = mysqli_query($conn, $sql);

		while($row = mysqli_fetch_assoc($query)) {
			$result[] = $row;			
		}
		
		foreach($result as $id => $key) {
			$iban = 'NL'.rand(10,99).$key['BI'].'0'.rand(100000000, 500000000);
			$result[$id]['iban'] = $iban;
		}
		
		$count = count($result);
		$rand = rand(0, $count);
	
	
		while(!isset($result[$rand])) {
			$rand = rand(0, $count);
		}
		
		return $result[$rand];
}
?>