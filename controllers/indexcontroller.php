<?php

/***
 *@ Default controller
 *@
 *@ Author: Utkarsh Kanabar
 *@ Created Date: 7/1/2011
 *@ Last Modified by: Utkarsh Kanabar
 *@ Modified Date: 8/14/2011
 *@
 *@ Purpose:
 *@ It's default controller which is loaded as by default
 ***/
 
class indexController extends abstractController{
	
	/*----------------------------------------------------------------------------
	Param  : None
	Return : Null
	Method : Default method called for first time or for home page
	-----------------------------------------------------------------------------*/
	function index(){
				
		
		
	}
	function register($param1){	
	    echo $param1."<br />";
		//echo $param2;
		
	}
	
	
	
}

?>
