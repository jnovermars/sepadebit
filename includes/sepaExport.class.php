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
 * @package   	ExportSepa
 * @author    	xleeuwx <info@xleeuwx.nl> 
 * @copyright  	2009 - 2014 xleeuwx
 * @license   	http://www.opensource.org/licenses/mit-license.html  MIT License
 * @since      	File available since Release 1.0.0
 */ 
class SepaExport {

	private $settings;
	private $exportPath;
	private $exportZipFile;
	private $sepaSettings;
	private $members;

	public function __construct($config, $members) {
		## -- Load settings
			$this->fgetSettings();
			
		## -- Set config
			$this->fsetSettings($config);
			
		## -- Set members
			$this->fsetMembers($members);			
		
	}
	
	public function fstartExport() {
		## -- Create export path
			$this->fcreateExportPath();
			
		## -- Start creating files
			$files = $this->fcreateSepaFile();
			
		## -- Zip files
			$zipFile = $this->fzipFiles($files);
		
		return $zipFile;
	}
	
	private function fgetSettings() {
		## -- Get settings class
			$settings = new Config();
		
		## -- Get settings
			$this->settings = $settings->fgetSettings("exportSepa");
			
		## -- Set settings
			$this->exportPath = $this->settings['exportPath'];
			$this->exportZipFile = $this->settings['exportZipFile'];
	}
	
	private function fsetSettings($config) {
		$this->sepaSettings = $config;
	}
	
	private function fsetMembers($members) {
		$this->members = $members;
	}
	
	private function fcreateExportPath() {
		$file_pie = explode("/", $this->exportPath);
			
		foreach($file_pie as $directory) {
			if(!isset($temp_path)) {
				$temp_path = $directory;
			} else {
				$temp_path = $temp_path.'/'.$directory;
			}
			if(!is_dir($temp_path)) {
				mkdir($temp_path);
			}
		}
	}
	
	private function fcreateSepaFile() {
		## -- Max members into a batch file
			$max_members_each_file = 100;
		
		## -- Member count (max members = $max_members_each_file)
			$count = 0;
		
		## -- Files count :
			$file_count = 1;
			
		## -- Default temp filename
			$file_name = $this->settings['exportPath'].'sepa_export_';
		
		## -- Start creating files i guess
			try {
				## -- Start Class
					$SEPASDD = new SEPASDD($this->sepaSettings);
					
				## -- Foreach Members
					foreach($this->members as $memberID => $member_info) {						
						## -- Check if max 100 members is reached
							if($count == $max_members_each_file) {
								## -- Put 100 members in a file
								
								## -- Save file
									file_put_contents($file_name.$file_count.'.xml', $SEPASDD->save());
								
								## -- Add saved file to array
									$files[] = $file_name.$file_count.'.xml';
								
								## -- Counter of files
									$file_count++;
									
								## -- Counter of members reset to zero
									$count = 0;	
									
								## -- Create new sepa class (new file in class)
									$SEPASDD = new sepaSDD($this->sepaSettings);
							}
							
						## -- Add member to file
							## -- count all members
								$count++;
							
							## -- Add member to object into sepa class
								$SEPASDD->addPayment($member_info);
					}
					
				## -- Save file
					file_put_contents($file_name.$file_count.'.xml', $SEPASDD->save());
					$files[] = $file_name.$file_count.'.xml';
				
				return $files;
			} catch(Exception $e) {
				echo $e->getMessage();
				exit;
			}
	}
	
	private function fzipFiles($files) {
		if(!is_array($files)) {
			trigger_error("No files in array", E_USER_ERROR);
			die();
		}
	
		## -- Zip files
			$zip_file = $this->createZipFile($files, $this->exportPath.'/'.$this->exportZipFile, TRUE);
			
		## -- Delete xml files
			foreach($files as $file) {
				unlink($file);
			}
		
		return $zip_file;
	}
	
	public function createZipFile($files = array(),$destination = '',$overwrite = true, $alt_filenames = false) {
		## -- Defaults
			if (!$alt_filenames) { $alt_filenames = $files; }
			
		## --if the zip file already exists and overwrite is false, return false
			if(file_exists($destination) && !$overwrite) { return false; }
			
		## --vars
			$valid_files = array();
			
		## --if files were passed in...
			if(is_array($files)) {
				## --cycle through each file
				foreach($files as $k => $file)
				{
					## --make sure the file exists
					if(file_exists($file))
					{
						$valid_files[] = $file;
						$valid_names[] = $alt_filenames[$k];
					}
				}
			}
			
		## -- if we have good files...
		if(count($valid_files)) {
			## -- create the archive
			$zip = new ZipArchive();
				if($zip->open($destination,$overwrite ? ZIPARCHIVE::OVERWRITE : ZIPARCHIVE::CREATE) !== true) {
					return false;
				}
				
			## -- add the files
				foreach($valid_files as $k => $file) {
					$fileX = explode("/", $file);
					$laatste = count($fileX)-1;
					$name = $fileX[$laatste];
					$name = $valid_names[$k];
					$zip->addFile($file,$name);
				}
			
			## -- close the zip
				$zip->close();

			## -- check to make sure the file exists
				return file_exists($destination);
		} else {
			return false;
		}
	}
	
}
	
?>