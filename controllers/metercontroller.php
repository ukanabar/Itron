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
 
class meterController extends abstractController{
	
	
	
	/*----------------------------------------------------------------------------
	Param  : None
	Return : Null
	Method : Default method called for first time or for home page
	-----------------------------------------------------------------------------*/
	function index(){		
		
		$intUserId = $this->session->getSessionVariable('intUserId');
		if(isset($_POST['add']) && (count($this->arrErrors)==0)){
			$arrMeterData['intMeterId'] = $_POST['meter_no'];
			$arrMeterData['intUserId']  = $intUserId;
			$intId                      = $this->meter->addMeterData($arrMeterData);
			$strMsg = (($intId!=0)?'Meter number added successfully.':'Sorry there was a problem.Try again later.');		    
		}
		$strMsg = '';
		
		$arrData   = $this->meter->getMeterDetailsFromUser($intUserId);
		$this->objSmarty->assign('blnLogin',$this->session->getSessionVariable('blnLogin'));
		$this->objSmarty->assign('arrData',$arrData);
		$this->objSmarty->assign('strMsg',$strMsg);
	}
	
	function remove($arrArg){
		$this->meter->removeMeterInfo($arrArg[0]);
		redirect('meter');
	
	}
	
	function hello(){
		echo "hello";exit;
	}
	    
		
}

?>
