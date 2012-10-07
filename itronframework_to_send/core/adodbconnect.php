<?php
/***
 *@ Adodb wraper class 
 *@
 *@ Author: Utkarsh Kanabar
 *@ Created Date: 7/1/2011
 *@ Last Modified by: Utkarsh Kanabar
 *@ Modified Date: 8/14/2011
 *@
 *@ Purpose:
 *@ This file overrides some default adodb functions and present them in application/developer friendly manner
 ***/
require_once('adodb/adodb.inc.php');

class adodbConnect{
	
	protected $objDb;
	protected $objRecordSet;
	protected $arrRow;
	protected $arrData;
	protected $arrAssocData = array();
	protected $blnResult;
	protected $intInsertId;
	protected $intRowCount;
	
	/*----------------------------------------------------------------------------
	Param  : None
	Return : Null
	Method : Default constructor creates adodb connection and connects to mysql db
	-----------------------------------------------------------------------------*/
	function __construct(){
		
		$this->objDb =  NewADOConnection('mysql') or die('Error: Unable to connect to database.');
		$this->objDb->Connect(HOST,USER,PASSWORD,DB);
	}
	
	/*----------------------------------------------------------------------------
	Param  : strSql
	Return : objRecordSet
	Method : Method takes sql query as arguement and returns result object so it 
	can be used for custom data generation in model
	-----------------------------------------------------------------------------*/
	function getRecordSet($strSql){
		$this->objRecordSet = $this->objDb->Execute($strSql);
		return $this->objRecordSet;
	}
	
	/*----------------------------------------------------------------------------
	Param  : strSql
	Return : arrRow
	Method : Method takes sql query as arguement and returns single(one) row
	-----------------------------------------------------------------------------*/
	function getRow($strSql){
		$this->arrRow =  $this->objDb->GetRow($strSql);
		return $this->arrRow;
	}
	
	/*----------------------------------------------------------------------------
	Param  : strSql
	Return : arrData
	Method : Method takes sql query as arguement and returns result data
	-----------------------------------------------------------------------------*/
	function getData($strSql){
		$this->arrData = $this->objDb->GetAll($strSql);
		return $this->arrData;
	}
	
	/*----------------------------------------------------------------------------
	Param  : strSql
	Return : arrData
	Method : Method takes sql query as arguement and returns result data in associative
	manner
	-----------------------------------------------------------------------------*/
	function getAssocData($strSql){
		$this->arrAssocData = $this->objDb->GetAssoc($strSql); 
		return $this->arrAssocData;
	}



	/*----------------------------------------------------------------------------
	Param  : strSql
	Return : intInsertId
	Method : Method takes sql query as arguement and returns inserted row id
	-----------------------------------------------------------------------------*/
	function executeInsert($strSql){
		$this->objDb->Execute($strSql);
		$this->intInsertId = $this->objDb->Insert_ID();
		return $this->intInsertId;
		
	}
	
	/*----------------------------------------------------------------------------
	Param  : strSql
	Return : introwCount
	Method : Method takes sql query as arguement and returns number of rows updated
	-----------------------------------------------------------------------------*/	
	
	function executeUpdate($strSql){
		$this->objDb->Execute($strSql);
		$this->intRowCount = $this->objDb->Affected_Rows();
		return $this->intRowCount;
	}
	
	
	/*----------------------------------------------------------------------------
	Param  : strSql
	Return : introwCount
	Method : Method takes sql query as arguement and returns number of rows deleted
	-----------------------------------------------------------------------------*/
	function executeDelete($strSql){
		$this->objDb->Execute($strSql);
		$this->intRowCount = $this->objDb->Affected_Rows();
		return $this->intRowCount;
	}
	/*----------------------------------------------------------------------------
	Param  : arrFields,arrTables,arrJoincond,strWhere
	Return : arrData
	Method : Method takes array of fields,array of table,array of join condition and string where clause as input and returns array of data
	-----------------------------------------------------------------------------*/
	function getJoinData($arrFields,$arrTables,$arrJoinCond,$strWhere=''){
		$strFields = implode(',',$arrFields);
		$strJoin   = '';
		if(isset($arrTables[1]) && is_array($arrJoinCond)){
			for($intCount=0;$intCount<count($arrJoinCond);$intCount++){
				$strJoin .= "LEFT JOIN ".$arrTables[$intCount+1]." ON ".$arrJoinCond[$intCount];
			}
		}		
		$strSql = "SELECT $strFields FROM ".$arrTables[0]." ".$strJoin." WHERE ".$strWhere;
		
		$this->arrData = $this->objDb->GetAll($strSql);
		return $this->arrData;
	}
	/*----------------------------------------------------------------------------
	Param  : None
	Return : Null
	Method : Default distructor closes the connection and distroys adodb connection object
	-----------------------------------------------------------------------------*/
	function __destruct(){
		$this->objDb->Close();
		$this->objDb = '';
	}


}

?>