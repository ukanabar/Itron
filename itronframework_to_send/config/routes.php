<?php
/***
 *@ Global url routing file
 *@
 *@ Author: Utkarsh Kanabar
 *@ Created Date: 7/1/2011
 *@ Last Modified by: Utkarsh Kanabar
 *@ Modified Date: 8/14/2011
 *@
 *@ Purpose:
 *@ This file contains all routing rules applied to sites
 ***/

$arrRoutes['default']               =  INDEX;
$arrRoutes['index/number']          = "index/number/:arg";
$arrRoutes['index/register/']       = "index/register/:arg";
$arrRoutes['index/register/search'] = "index/register/search/:arg";
$arrRoutes['index/index']           = "index/:arg";
$arrRoutes['meter/remove']          = "meter/remove/:arg";
$arrRoutes['creditcard/remove']     = "creditcard/remove/:arg";





?>