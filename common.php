<?php
/***
 *@ Global Common file
 *@
 *@ Author: Utkarsh Kanabar
 *@ Created Date: 7/1/2011
 *@ Last Modified by: Utkarsh Kanabar
 *@ Modified Date: 8/14/2011
 *@
 *@ Purpose:
 *@ This file strictly contains code that needs to be executed whenever any script
 *@ starts, like global DB connection object,global template object
 ***/
require_once('config/constants.php');
require_once('config/config.php');
require_once('config/functions.php');

$arrIncludePaths = array(
						APPLICATION_PATH,
						APPLICATION_PATH.DIRECTORY_SEPARATOR.CONFIGPATH,
                        APPLICATION_PATH.DIRECTORY_SEPARATOR.COREPATH,
						APPLICATION_PATH.DIRECTORY_SEPARATOR.MODELPATH,
						APPLICATION_PATH.DIRECTORY_SEPARATOR.CONTROLLERPATH,
						APPLICATION_PATH.DIRECTORY_SEPARATOR.LIBRARYPATH,
						APPLICATION_PATH.DIRECTORY_SEPARATOR.SMARTYPATH,
						APPLICATION_PATH.DIRECTORY_SEPARATOR.LANGPATH,
						get_include_path());
				   
set_include_path(implode(PATH_SEPARATOR,$arrIncludePaths));
function __autoload($strClassName){
    $strFileName = str_replace('\\',DIRECTORY_SEPARATOR,strtolower($strClassName)) .'.php';
	require_once $strFileName;
}


$objSmarty      = new smartyTemplate();
$objDb          = new adodbConnect();
$objSession     = new session();
$objSession->startSession();

?>
