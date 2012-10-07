<?php
/***
 *@ Site home page index.php
 *@
 *@ Author: Utkarsh Kanabar
 *@ Created Date: 7/1/2011
 *@ Last Modified by: Utkarsh Kanabar
 *@ Modified Date: 8/14/2011
 *@
 *@ Purpose:
 *@ This file routes url to proper location and dispathes it to proper conroller
 ***/

require_once "common.php";
if(HTTPSENABLE){
	redirectToHTTPS();
}
$strUri = strtolower($_SERVER['REQUEST_URI']);
$strCurDirectory = CURRENT_DIRECTORY;
$strUri = str_replace(array($strCurDirectory,'//'),array('','/'),$strUri);
$arrMatches = '';
if(is_array($arrRoutes)){
	foreach($arrRoutes as $strKey=>$strValue){
		$strPatern = "/^\/".str_replace('/','\/',str_replace(':arg',ARGREG,$strValue))."$/";
		preg_match($strPatern, $strUri, $arrMatches);
		if(count($arrMatches)>1){
			unset($arrMatches[0]);
			$strUri   = "/".$strKey;
			break;
		 }
	}
 
}

if($strUri == '/' || $strUri=='/index'){
	$strPath           = '';
	$strController     = $arrRoutes['default'];
	$strModelName      = $strController."model";
	$strControllerName = $strController."controller";	
	$strActionName     = $strController;
} else {
 	$arrUriParts       = preg_split('/[\/\\\]/',$strUri);
	$intUriCount       = count($arrUriParts);
	if($arrUriParts[$intUriCount-1]==''){
		unset($arrUriParts[$intUriCount-1]);
		$intUriCount = $intUriCount -1;
	}
	$strPrefix         = '';
	
	for($intCount=1;$intCount<$intUriCount;$intCount++){
		    $arrParts[] = $arrUriParts[$intCount];
	}
	$arrUsedParts      = $arrParts;
	$strController     = $arrParts[count($arrParts)-1];
	$strControllerName = $strController."controller";
	$strModelName      = $strController."model";
	$strActionName     = INDEX;
	
	
	unset($arrParts[count($arrParts)-1]);
	$strPath = implode("/",$arrParts);
		
	if(!file_exists(CONTROLLERPATH."/".$strPath."/".$strControllerName.".php")){
		$strActionName     = $arrUsedParts[count($arrUsedParts)-1];
		$strController     = $arrUsedParts[count($arrUsedParts)-2];
		unset($arrUsedParts[count($arrUsedParts)-1]);
		unset($arrUsedParts[count($arrUsedParts)-1]);
		$strControllerName = $strController."controller";
		$strModelName      = $strController."model";
		$strPath = implode("/",$arrUsedParts);
    }	
	    
}	
		
		require_once $strPath.$strModelName.".php";
		
	       
		$objModel      = new $strModelName($objDb);
		$objValidate   = new validation($objModel,$objSmarty);
		$arrErrors     = $objValidate->getErrors();
        
		
		require_once $strPath.$strControllerName.".php";
		$objController = new $strControllerName($strPath,$strController,$strActionName,$objSmarty,$objDb,$objModel,$objSession,$arrErrors);
		if(isset($arrMatches[1]) && !empty($arrMatches[1])){
			
			if(method_exists($objController,$arrMatches[1])){
				$objController->setAction($arrMatches[1]);
			}
		}
		
		
		//Assigning Paypal header to work in Paypal in Internet Explorer		
		$objSmarty->assign('strPaypalHeader',PAYPAL_HEADER);
		$objSmarty->assign('strPath',SITE_URL);
		$objController->dispatch($arrMatches);
		$objController->assignErrors();
		
		
		
		$objController->render();
		

?>


