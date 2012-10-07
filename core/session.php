<?php
/***
 *@ Global functions file
 *@
 *@ Author: Utkarsh Kanabar
 *@ Created Date: 11/9/2011
 *@ Last Modified by: Utkarsh Kanabar
 *@ Modified Date: 11/9/2011
 *@
 *@ Purpose:
 *@ class containing session management functions
 ***/
set_time_limit(0);
class session{
	
	function startSession(){
		session_start(); 
	}
	
	function setSessionVariable($strKey,$strValue){
		$_SESSION[$strKey] = $strValue;
	}
	
	function unsetSessionVariable($strKey){
		unset($_SESSION[$strKey]);
	}
	
	function getSessionVariable($strKey){
		return (isset($_SESSION[$strKey])?$_SESSION[$strKey]:'');
	}
	
	function destroySession(){
		session_destroy();
	}

}