<?php
/***
 *@ Global Constants file
 *@
 *@ Author: Utkarsh Kanabar
 *@ Created Date: 7/1/2011
 *@ Last Modified by: Utkarsh Kanabar
 *@ Modified Date: 8/14/2011
 *@
 *@ Purpose:
 *@ This file contains all the constants required throughout the application
 ***/
 
define('PRODUCTIONHOST','production.com');
define('TESTHOST','test.com');
define('APPLICATION_PATH',dirname(dirname(__FILE__)));
define("CLASS_DIR", dirname(__FILE__));
define('INDEX','index');
define('CONFIGPATH','config');
define('COREPATH','core');
define('MODELPATH','models');
define('CONTROLLERPATH','controllers');
define('VIEWPATH','views');
define('LIBRARYPATH','libs');
define('SMARTYPATH','core/smarty/libs/sysplugins');
define('ARGREG','(.*)');
define('LANGPATH','languages');
define('SMSTESTURL','http://colesolution.com:13013/cgi-bin/sendsms?username=tester&password=foobar&from=95370&');
define('PAYPAL_HEADER',header('P3P:CP="IDC DSP COR ADM DEVi TAIi PSA PSD IVAi IVDi CONi HIS OUR IND CNT"'));
define('CURRENT_DIRECTORY',basename(getcwd(), ".d"));
define('VALIDATE','validate');
define('HTTPSENABLE',0);
?>
