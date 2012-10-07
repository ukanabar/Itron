<?php
/***
 *@ Controller interface
 *@
 *@ Author: Utkarsh Kanabar
 *@ Created Date: 7/1/2011
 *@ Last Modified by: Utkarsh Kanabar
 *@ Modified Date: 8/14/2011
 *@
 *@ Purpose:
 *@ To create interface for abstract controller is best practice so this interface is created to declare a methots which will be further defined in abstract controller 
 ***/

interface controllerInterface
{
	public function dispatch($arrParam);
}
