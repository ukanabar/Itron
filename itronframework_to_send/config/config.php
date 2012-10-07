<?php
/***
 *@ Global configuration files
 *@
 *@ Author: Utkarsh Kanabar
 *@ Created Date: 7/1/2011
 *@ Last Modified by: Utkarsh Kanabar
 *@ Modified Date: 8/14/2011
 *@
 *@ Purpose:
 *@ This file contains logic to set the site's current host and set config param as per that
 ***/

$strServer = php_uname('n');
switch ($strServer) {
	case PRODUCTIONHOST:
	 	include_once "hosts/production.config.php";
		break;
	case TESTHOST:
		include_once "hosts/test.config.php";
		break;
	default:
		include_once "hosts/local.config.php";
		break;
}
include_once 'routes.php';
?>