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
 * @package   	Members
 * @author    	xleeuwx <info@xleeuwx.nl> 
 * @copyright  	2009 - 2014 xleeuwx
 * @license   	http://www.opensource.org/licenses/mit-license.html  MIT License
 * @since      	File available since Release 1.0.0
 */ 

/**
 *
 * Get Members from database
 * @input none
 * @return (array) -> the return gives back a array of members with the following structure
 *    - array('memberID' => array('type', 'collection_date', 'mandate_id', 'mandate_date', 'description', 'name', 'IBAN', 'BIC', 'amount'), 'memberID' ... );
 *
 */	
 
function fgetMembers() {

	$db = new database();
	$conn = $db->fgetConnection();
	
	$members = array();
	$members_data = array();
	
	## -- Get clients
		$sql = "SELECT `mem`.`memberID`, `mem`.`first_name`, `mem`.`middle_name`, `mem`.`last_name`, `mem`.`bank_account`, `mem`.`bank_bic`, `mem`.`bank_city`, `sub`.`amount` 
				  FROM `member_info` `mem` 
				  JOIN `member_subscriptions` `sub` ON (`mem`.`memberID` = `sub`.`memberID`) 
				  WHERE `mem`.`bank_account` != '' AND `mem`.`bank_account` IS NOT NULL";
		
		$query = mysqli_query($conn, $sql);
		
		while($row = mysqli_fetch_assoc($query)) {
			$members[$row['memberID']] = $row;
		}
				
		
		## -- Filter if member have iban (dutch only supported)
			foreach($members as $memberID => $member_info) {
				## -- Check IBAN
					if(substr($member_info['bank_account'], 0, 2) != 'NL') {
						unset($members[$memberID]);
						continue;
					}
					
					$BI = substr($member_info['bank_account'], 4, 4);
					
					if(strlen($member_info['bank_bic']) == 8) {
						$BIC = $member_info['bank_bic'].'XXX';
					} else {
						$BIC = $member_info['bank_bic'];
					}
				
				## -- Check BIC
					$sql = "SELECT `BIC` FROM `bic_codes` WHERE `BIC` = '$BIC' OR `BI` = '$BI' LIMIT 1";
					
					$query = mysqli_query($conn, $sql);
		
					while($row = mysqli_fetch_assoc($query)) {
						$result = $row['BIC'];
					}
					
					if(isset($result) && strlen($result) == 11) {
						## -- OK
							$members[$memberID]['bank_bic'] = $result;
					} else {
						## -- NOT VALID BIC
						unset($members[$memberID]);
						continue;
					}
			}
			
		if(isset($members) && is_array($members)) { 
			foreach($members as $memberID => $member_info) {
				if(!empty($member_info['first_name']) && !empty($member_info['last_name'])) {
					$payment_config = array("type" => "FRST",
											"collection_date" => date("Y-m-d"),
											"mandate_id" => rand(0,11000),
											"mandate_date" => date("Y-m-d"),
											"description" => "Automatische Betaling"
											);
					$members_data[$memberID] = $payment_config;
					$members_data[$memberID]["name"] = $member_info['first_name'].' '.(!empty($member_info['middle_name']) ? $member_info['middle_name'].' ': '').$member_info['last_name'];
					$members_data[$memberID]["IBAN"] = $member_info['bank_account'];
					$members_data[$memberID]["BIC"] = $member_info['bank_bic'];
					$members_data[$memberID]["amount"] = $member_info['amount'];
				}
			}
		}
			
		return $members_data;
}

?>