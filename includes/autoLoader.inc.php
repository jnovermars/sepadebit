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
 * @package   	Autoloader
 * @author    	xleeuwx <info@xleeuwx.nl> 
 * @copyright  	2009 - 2014 xleeuwx
 * @license   	http://www.opensource.org/licenses/mit-license.html  MIT License
 * @since      	File available since Release 1.0.0
 */ 


/**
 *
 * Create Auto Loader
 *
 * @param string $class -> a valide class name
 * @return bool
 * 
 */

function classAutoLoader($class) {
    if(class_exists($class)) {
        return false;
    }

    // Relative include
    $classFile = $class.'.class.php';
    if(file_exists($classFile)) {
        include $classFile;
        return true;
    }

    // Absolute include
    $classFile = dirname(__DIR__).'/includes/'.$class.'.class.php';
    if(file_exists($classFile)) {
        include $classFile;
        return true;
    }

    // Dynamic include
    $classFile = $_SERVER['DOCUMENT_ROOT'].'/includes/'.$class.'.class.php';
    if(file_exists($classFile)) {
        include $classFile;
        return true;
    }
    return false;
}



/**
 * 
 * Register Auto Loader as new Auto loader
 * 
 */

spl_autoload_register('classAutoLoader');