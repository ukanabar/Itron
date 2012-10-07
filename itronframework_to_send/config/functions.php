<?php
/***
 *@ Global functions file
 *@
 *@ Author: Utkarsh Kanabar
 *@ Created Date: 10/9/2011
 *@ Last Modified by: Utkarsh Kanabar
 *@ Modified Date: 10/9/2011
 *@
 *@ Purpose:
 *@ Global functions file
 ***/


function redirect($strUrl){
	$strRedirectUrl = "http://";
	if(HTTPSENABLE){
		$strRedirectUrl = "https://";
	}
	$strRedirectUrl.= $_SERVER['HTTP_HOST']."/".$strUrl;
	header('Location:'.$strRedirectUrl);
	exit;
}


function redirectToHTTPS()
{
	if($_SERVER['HTTPS']!=="on")
	{
		$redirect= "https://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
		header("Location:$redirect");
	}
}


?>