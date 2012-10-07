<?php
/***
 *@ curl library class 
 *@
 *@ Author: Utkarsh Kanabar
 *@ Created Date: 8/24/2011
 *@ Last Modified by: Utkarsh Kanabar
 *@ Modified Date: 8/24/2011
 *@
 *@ Purpose:
 *@ This file wraper class to default php curl so it can be used dynamically within a framework for 
 ***/
class curl{
	
protected $objCurl;
protected $strUrl;
protected $strResponse;

function __construct($strUrl){
	 $this->objCurl = curl_init() or die("Couldn't initialize a cURL handle");
	 $this->strUrl  = $strUrl;
}


function setCurlOptions(){
	 curl_setopt($this->objCurl, CURLOPT_URL,$this->strUrl);
     curl_setopt($this->objCurl, CURLOPT_HEADER, 0);
     curl_setopt($this->objCurl, CURLOPT_RETURNTRANSFER, 1);
}


function sendCurlRequest(){
	 $this->strResponse = curl_exec($this->objCurl);
	 return $this->strResponse;
}

function __destruct(){
	 curl_close($this->objCurl);
}

}


?>