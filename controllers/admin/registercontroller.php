<?php
class registerController extends abstractController{
	
	function index($arrArgs){
		print_R($arrArgs);
	}
	function login(){
		echo "Inside Login Controller";
		
	}
	function search($arrParam){
		print_R($arrParam);
		//print_r($this->register->getData(array('e.id','e.name','c.name'),array('emp e','company c'),array('e.company_id = c.id'),'e.id='.$arrParam[0]));
		//print_r($this->register->getData(array('*'),array('emp e','company c'),array('e.company_id = c.id'),'e.id='.$arrParam[0]));
		print_R($this->register->getData());
	}


}
