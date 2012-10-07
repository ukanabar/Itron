<?php
/***
 *@ Smarty wraper class 
 *@
 *@ Author: Utkarsh Kanabar
 *@ Created Date: 7/1/2011
 *@ Last Modified by: Utkarsh Kanabar
 *@ Modified Date: 8/14/2011
 *@
 *@ Purpose:
 *@ This file overrides some smarty template engine class functions and present them in application/developer friendly manner
 ***/
require_once('smarty/libs/Smarty.class.php');

class smartytemplate{
	protected $objSmarty;
	
	/*----------------------------------------------------------------------------
	Param  : None
	Return : Null
	Method : Default constructor creates smarty object
	-----------------------------------------------------------------------------*/
	function __construct(){
		$this->objSmarty                   = new Smarty;
		$this->objSmarty->allow_php_tag    = true;
	}
	
	/*----------------------------------------------------------------------------
	Param  : None
	Return : objSmarty
	Method : Function returns smarty template objects 
	-----------------------------------------------------------------------------*/
	function getSmartyObject(){
		return $this->objSmarty;
	}
	
	/*----------------------------------------------------------------------------
	Param  : strName,strValue
	Return : Null
	Method : This function overrides assign function of smarty.It assignes value to smarty variable so it can be used in template
	-----------------------------------------------------------------------------*/
	function assign($strName,$strValue){
		$this->objSmarty->assign($strName,$strValue);		
	}
	
	/*----------------------------------------------------------------------------
	Param  : strFileName
	Return : Null
	Method : This function overrides display function of smarty.It filename as arguement and displays template form views folder
	-----------------------------------------------------------------------------*/
	function display($strFileName){
		$strFilePath = str_replace('\\','/',APPLICATION_PATH);
		if(file_exists($strFilePath."/".VIEWPATH."/".$strFileName)){
			$this->objSmarty->display("file:".$strFilePath."/".VIEWPATH."/".$strFileName);
			exit;
		} else {
			throw new Exception("Please create template file in views as $strFileName");
		}
	}	
	
	/*----------------------------------------------------------------------------
	Param  : None
	Return : Null
	Method : Default distructor distroys smarty object
	-----------------------------------------------------------------------------*/
	function __destruct(){
		$this->objSmarty = '';
	}
	
		/*----------------------------------------------------------------------------
	Param  : strFileName
	Return : Null
	Method : This function overrides fetch function of smarty.It filename as arguement and fetches template form views folder
	-----------------------------------------------------------------------------*/
	function fetch($strFileName){
		$strFilePath = str_replace('\\','/',APPLICATION_PATH);
		if(file_exists($strFilePath."/".VIEWPATH."/".$strFileName)){
			
			return $this->objSmarty->fetch("file:".$strFilePath."/".VIEWPATH."/".$strFileName);
			
		} else {
			throw new Exception("Please create template file in views as $strFileName");
		}
	}	
		
}



?>