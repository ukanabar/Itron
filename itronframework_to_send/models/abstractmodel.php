<?php
/***
 *@ Abstract model
 *@
 *@ Author: Utkarsh Kanabar
 *@ Created Date: 7/1/2011
 *@ Last Modified by: Utkarsh Kanabar
 *@ Modified Date: 8/14/2011
 *@
 *@ Purpose:
 *@ This should be inherited by all models written so they will get adodb object for data interaction
 ***/
abstract class abstractModel{
	
	protected $objDb;
	
	/*----------------------------------------------------------------------------
	Param  : None
	Return : Null
	Method : Default constructor creates db object
	-----------------------------------------------------------------------------*/
	public function __construct($objDb){
		$this->objDb = $objDb;
	}
	
	
	
}