<?php
/***
 *@ Abstract controller
 *@
 *@ Author: Utkarsh Kanabar
 *@ Created Date: 7/1/2011
 *@ Last Modified by: Utkarsh Kanabar
 *@ Modified Date: 8/14/2011
 *@
 *@ Purpose:
 *@ This should be inherited by all controllers written as it dispatches actual function called in conroller with parameters and renders view for that function 
 ***/


abstract class abstractController implements controllerInterface
{
	protected $strController;	
	protected $objSmarty;
	protected $strPath;
	protected $objModel;
	protected $session;
	protected $input;
	protected $strAction;
	protected $arrErrors;
	protected $objDb; 
	
	
	/*----------------------------------------------------------------------------
	Param  : strPath,strController,strAction,objSmarty,objModel
	Return : Null
	Method : Default constructor assignes controller default values
	-----------------------------------------------------------------------------*/
	public function __construct($strPath,$strController,$strAction,$objSmarty,$objDb,$objModel,$objSession,$arrErrors)
	{
		
		$this->strController  = $strController;
		$this->strAction      = $strAction;
		$this->objSmarty      = $objSmarty;		
		$this->strPath        = $strPath;
		$this->arrErrors      = $arrErrors;
		$this->$strController = $objModel;
		$this->session        = $objSession;
		$this->objDb          = $objDb;
	}
	
	function setAction($strAction){
		$this->strAction = $strAction;
	}
	/*----------------------------------------------------------------------------
	Param  : None
	Return : Null
	Method : It renders view for particular controller
	-----------------------------------------------------------------------------*/
	public function render()
	{
		$this->strController           = (($this->strController == INDEX)?'':$this->strController);
		$this->objSmarty->display($this->strPath.$this->strController."/".$this->strAction.".tpl");
	}
	
	/*----------------------------------------------------------------------------
	Param  : arrParam
	Return : Null
	Method : It dispathes request to requested controller's function with parameter passed
	-----------------------------------------------------------------------------*/
	public function dispatch($arrParam)
	{
		$strMethodName           = $this->strAction;
		if (method_exists($this,$strMethodName)){
			call_user_func_array(array($this,$strMethodName),$arrParam);			
		} else {
			throw new Exception("Please implement a function called $strMethodName!");
		}
	}
	
	public function assignErrors(){
		$this->objSmarty->assign('arrFormErrors',$this->arrErrors);
	}
	
	/*----------------------------------------------------------------------------
	Param  : None
	Return : Null
	Method : Default destructor unsets controller default values
	-----------------------------------------------------------------------------*/
	public function __destruct(){
		
		$this->strController  = '';
		$this->strAction      = '';
		$this->objSmarty      = '';
		$this->strPath        = '';
		
	}
	
}
