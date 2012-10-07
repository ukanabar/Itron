<?php

/*--------------------------------------------------------------------------------------------*\

  validation.php
  --------------

  v2.3.3, Apr 2010

  This script provides generic validation for any web form. For a discussion and example usage 
  of this script, go to http://www.benjaminkeen.com/software/php_validation

  This script is written by Ben Keen with additional code contributed by Mihai Ionescu and 
  Nathan Howard. It is free to distribute, to re-write - to do what ever you want with it.

  Before using it, please read the following disclaimer. 

  THIS SOFTWARE IS PROVIDED ON AN "AS-IS" BASIS WITHOUT WARRANTY OF ANY KIND. BENJAMINKEEN.COM 
  SPECIFICALLY DISCLAIMS ANY OTHER WARRANTY, EXPRESS OR IMPLIED, INCLUDING ANY WARRANTY OF 
  MERCHANTABILITY OR FITNESS FOR A PARTICULAR PURPOSE. IN NO EVENT SHALL BENJAMINKEEN.COM BE 
  LIABLE FOR ANY CONSEQUENTIAL, INDIRECT, SPECIAL OR INCIDENTAL DAMAGES, EVEN IF BENJAMINKEEN.COM 
  HAS BEEN ADVISED OF THE POSSIBILITY OF SUCH POTENTIAL LOSS OR DAMAGE. USER AGREES TO HOLD 
  BENJAMINKEEN.COM HARMLESS FROM AND AGAINST ANY AND ALL CLAIMS, LOSSES, LIABILITIES AND EXPENSES.

\*--------------------------------------------------------------------------------------------*/


/*--------------------------------------------------------------------------------------------*\
  Function: validateFields()
  Purpose:  generic form field validation.
  Parameters: field - the POST / GET fields from a form which need to be validated.
              rules - an array of the validation rules. Each rule is a string of the form:

   "[if:FIELDNAME=VALUE,]REQUIREMENT,fieldname[,fieldname2 [,fieldname3, date_flag]],error message"
  
              if:FIELDNAME=VALUE,   This allows us to only validate a field 
                          only if a fieldname FIELDNAME has a value VALUE. This 
                          option allows for nesting; i.e. you can have multiple 
                          if clauses, separated by a comma. They will be examined
                          in the order in which they appear in the line.

              Valid REQUIREMENT strings are: 
                "required"     - field must be filled in
                "digits_only"  - field must contain digits only
                "is_alpha"     - field must only contain alphanumeric characters (0-9, a-Z)
                "custom_alpha" - field must be of the custom format specified.
                      fieldname:  the name of the field
                      fieldname2: a character or sequence of special characters. These characters are:
                          L   An uppercase Letter.          V   An uppercase Vowel.
                          l   A lowercase letter.           v   A lowercase vowel.
                          D   A letter (upper or lower).    F   A vowel (upper or lower).
                          C   An uppercase Consonant.       x   Any number, 0-9.
                          c   A lowercase consonant.        X   Any number, 1-9.
                          E   A consonant (upper or lower).
                "reg_exp"      - field must match the supplied regular expression.  
                      fieldname:  the name of the field
                      fieldname2: the regular expression
                      fieldname3: (optional) flags for the reg exp (like i for case insensitive
                "letters_only" - field must only contains letters (a-Z)

                "length=X"     - field has to be X characters long
                "length=X-Y"   - field has to be between X and Y (inclusive) characters long
                "length>X"     - field has to be greater than X characters long
                "length>=X"    - field has to be greater than or equal to X characters long
                "length<X"     - field has to be less than X characters long
                "length<=X"    - field has to be less than or equal to X characters long

                "valid_email"  - field has to be valid email address
                "valid_date"   - field has to be a valid date
                      fieldname:  MONTH 
                      fieldname2: DAY 
                      fieldname3: YEAR
                      date_flag:  "later_date" / "any_date"
                "same_as"     - fieldname is the same as fieldname2 (for password comparison)

                "range=X-Y"    - field must be a number between the range of X and Y inclusive
                "range>X"      - field must be a number greater than X
                "range>=X"     - field must be a number greater than or equal to X
                "range<X"      - field must be a number less than X
                "range<=X"     - field must be a number less than or equal to X

  
  Comments:   With both digits_only, valid_email and is_alpha options, if the empty string is passed 
              in it won't generate an error, thus allowing validation of non-required fields. So,
              for example, if you want a field to be a valid email address, provide validation for 
              both "required" and "valid_email".
\*--------------------------------------------------------------------------------------------*/

/***
 *@ Validation class for fields
 *@
 *@ Author: Ben Keen,Mihai Ionescu and Nathan Howard.
 *@ Last Modified by: Utkarsh Kanabar
 *@ Modified Date: 9/10/2011
 *@
 *@ Purpose:
 *@ To add validation api to itron framework
 ***/
class validation{
	
protected $arrFields;
protected $arrRules;
protected $arrErrors;
protected $objModel;
protected $objSmarty;


function __construct($objModel,$objSmarty){
	$this->objModel   = $objModel;
	$this->objSmarty  = $objSmarty;
	$this->arrErrors  = '';
}
	
function getErrors(){
	if(method_exists($this->objModel,VALIDATE)){
		$this->arrRules  = $this->objModel->validate();
		$this->arrErrors = $this->validateFields($_POST,$this->arrRules,$this->objModel);				
	}
	return $this->arrErrors;
}
function validateFields($arrFields,$arrRules,$objModel)
{ 
  $arrErrors = array();
  
  // loop through rules
  for ($intCount=0; $intCount<count($arrRules); $intCount++)
  {
    // split row into component parts 
    $strRow = explode(",", $arrRules[$intCount]);
    
    // while the row begins with "if:..." test the condition. If true, strip the if:..., part and 
    // continue evaluating the rest of the line. Keep repeating this while the line begins with an 
    // if-condition. If it fails any of the conditions, don't bother validating the rest of the line
    $blnSatisfiesIfConditions = true;
    while (preg_match("/^if:/", $strRow[0]))
    {
      $strCondition = preg_replace("/^if:/", "", $strRow[0]);

      // check if it's a = or != test
      $strComparison = "equal";
      $arrParts = array();
      if (preg_match("/!=/", $strCondition))
      {
        $arrParts = explode("!=", $strCondition);
        $strComparison = "not_equal";
      }
      else 
        $arrParts = explode("=", $strCondition);

      $strFieldToCheck = $arrParts[0];
      $strValueToCheck = $arrParts[1];
     
      // if the VALUE is NOT the same, we don't need to validate this field. Return.
      if ($strComparison == "equal" && $arrFields[$strFieldToCheck] != $strValueToCheck)
      {
        $blnSatisfiesIfConditions = false;
        break;
      }
      else if ($strComparison == "not_equal" && $arrFields[$strFieldToCheck] == $strValueToCheck)
      {
        $blnSatisfiesIfConditions = false;
        break;      
      }
      else 
        array_shift($strRow);    // remove this if-condition from line, and continue validating line
    }

    if (!$blnSatisfiesIfConditions)
      continue;


    $strRequirement = $strRow[0];
    $strFieldName  = $strRow[1];

    // depending on the validation test, store the incoming strings for use later...
    if (count($strRow) == 6)        // valid_date
    {
      $strFieldName2   = $strRow[2];
      $strFieldName3   = $strRow[3];
      $blnDateFlag     = $strRow[4];
      $strErrorMessage = $strRow[5];
    }
    else if (count($strRow) == 5)     // reg_exp (WITH flags like g, i, m)
    {
      $strFieldName2   = $strRow[2];
      $strFieldName3   = $strRow[3];
      $strErrorMessage = $strRow[4];
    }
    else if (count($strRow) == 4)     // same_as, custom_alpha, reg_exp (without flags like g, i, m)
    {
      $strFieldName2   = $strRow[2];
      $strErrorMessage = $strRow[3];
    }
    else
      $strErrorMessage = $strRow[2];    // everything else!


    // if the requirement is "length=...", rename requirement to "length" for switch statement
    if (preg_match("/^length/", $strRequirement))
    {
      $strLengthRequirements = $strRequirement;
      $strRequirement         = "length";
    }

    // if the requirement is "range=...", rename requirement to "range" for switch statement
    if (preg_match("/^range/", $strRequirement))
    {
      $strRangeRequirements = $strRequirement;
      $strRequirement        = "range";
    }


    // now, validate whatever is required of the field
    switch ($strRequirement)
    {
      case "required":
	    if (isset($arrFields[$strFieldName]) && $arrFields[$strFieldName] == "")
          $arrErrors[$strFieldName] = $strErrorMessage;
        break;

      case "digits_only":       
        if (isset($arrFields[$strFieldName]) && preg_match("/\D/", $arrFields[$strFieldName]))
          $arrErrors[$strFieldName] = $strErrorMessage;
        break;

      case "letters_only": 
        if (isset($arrFields[$strFieldName]) && preg_match("/[^a-zA-Z]/", $arrFields[$strFieldName]))
          $arrErrors[$strFieldName] = $strErrorMessage;
        break;

      // doesn't fail if field is empty
      case "valid_email":
				$strRegExp="/^[a-z0-9]+([_+\\.-][a-z0-9]+)*@([a-z0-9]+([\.-][a-z0-9]+)*)+\\.[a-z]{2,}$/i";    
        if (isset($arrFields[$strFieldName]) && !empty($arrFields[$strFieldName]) && !preg_match($strRegExp, $arrFields[$strFieldName]))
          $arrErrors[$strFieldName] = $strErrorMessage;
        break;

      case "length":
        $strComparisonRule = "";
        $strRuleString     = "";

        if      (preg_match("/length=/", $strLengthRequirements))
        {
          $strComparisonRule = "equal";
          $strRuleString = preg_replace("/length=/", "", $strLengthRequirements);
        }
        else if (preg_match("/length>=/", $strLengthRequirements))
        {
          $strComparisonRule = "greater_than_or_equal";
          $strRuleString = preg_replace("/length>=/", "", $strLengthRequirements);
        }
        else if (preg_match("/length<=/", $strLengthRequirements))
        {
          $strComparisonRule = "less_than_or_equal";
          $strRuleString = preg_replace("/length<=/", "", $strLengthRequirements);
        }
        else if (preg_match("/length>/", $strLengthRequirements))
        {
          $strComparisonRule = "greater_than";
          $strRuleString = preg_replace("/length>/", "", $strLengthRequirements);
        }
        else if (preg_match("/length</", $strLengthRequirements))
        {
          $strComparisonRule = "less_than";
          $strRuleString = preg_replace("/length</", "", $strLengthRequirements);
        }

        switch ($strComparisonRule)
        {
          case "greater_than_or_equal":
            if (isset($arrFields[$strFieldName]) && !(strlen($arrFields[$strFieldName]) >= $strRuleString))
              $arrErrors[$strFieldName] = $strErrorMessage;
            break;
          case "less_than_or_equal":
            if (isset($arrFields[$strFieldName]) && !(strlen($arrFields[$strFieldName]) <= $strRuleString))
              $arrErrors[$strFieldName] = $strErrorMessage;
            break;
          case "greater_than":
            if (isset($arrFields[$strFieldName]) && !(strlen($arrFields[$strFieldName]) > $strRuleString))
              $arrErrors[$strFieldName] = $strErrorMessage;
            break;
          case "less_than":
            if (isset($arrFields[$strFieldName]) && !(strlen($arrFields[$strFieldName]) < $strRuleString))
              $arrErrors[$strFieldName] = $strErrorMessage;
            break;
          case "equal":
            // if the user supplied two length fields, make sure the field is within that range
            if (preg_match("/-/", $strRuleString))
            {
              list($intStart, $intEnd) = explode("-", $strRuleString);
              if (isset($arrFields[$strFieldName]) && (strlen($arrFields[$strFieldName]) < $intStart || strlen($arrFields[$strFieldName]) > $intEnd))
                $arrErrors[$strFieldName] = $strErrorMessage;
            }
            // otherwise, check it's EXACTLY the size the user specified 
            else
            {
              if (isset($arrFields[$strFieldName]) && strlen($arrFields[$strFieldName]) != $strRuleString)
                $arrErrors[$strFieldName] = $strErrorMessage;
            }     
            break;       
        }
        break;

      case "range":
        $strComparisonRule = "";
        $strRuleString     = "";

        if      (preg_match("/range=/", $strRangeRequirements))
        {
          $strComparisonRule = "equal";
          $strRuleString = preg_replace("/range=/", "", $strRangeRequirements);
        }
        else if (preg_match("/range>=/", $strRangeRequirements))
        {
          $strComparisonRule = "greater_than_or_equal";
          $strRuleString = preg_replace("/range>=/", "", $strRangeRequirements);
        }
        else if (preg_match("/range<=/", $strRangeRequirements))
        {
          $strComparisonRule = "less_than_or_equal";
          $strRuleString = preg_replace("/range<=/", "", $strRangeRequirements);
        }
        else if (preg_match("/range>/", $strRangeRequirements))
        {
          $strComparisonRule = "greater_than";
          $strRuleString = preg_replace("/range>/", "", $strRangeRequirements);
        }
        else if (preg_match("/range</", $strRangeRequirements))
        {
          $strComparisonRule = "less_than";
          $strRuleString = preg_replace("/range</", "", $strRangeRequirements);
        }
        
        switch ($strComparisonRule)
        {
          case "greater_than":
            if (isset($arrFields[$strFieldName]) && !($arrFields[$strFieldName] > $strRuleString))
              $arrErrors[$strFieldName] = $strErrorMessage;
            break;
          case "less_than":
            if (isset($arrFields[$strFieldName]) && !($arrFields[$strFieldName] < $strRuleString))
              $arrErrors[$strFieldName] = $strErrorMessage;
            break;
          case "greater_than_or_equal":
            if (isset($arrFields[$strFieldName]) && !($arrFields[$strFieldName] >= $strRuleString))
              $arrErrors[$strFieldName] = $strErrorMessage;
            break;
          case "less_than_or_equal":
            if (isset($arrFields[$strFieldName]) && !($arrFields[$strFieldName] <= $strRuleString))
              $arrErrors[$strFieldName] = $strErrorMessage;
            break;
          case "equal":
            list($intStart, $intEnd) = explode("-", $strRuleString);

            if (isset($arrFields[$strFieldName]) && ($arrFields[$strFieldName] < $intStart) || ($arrFields[$strFieldName] > $intEnd))
              $arrErrors[$strFieldName] = $strErrorMessage;
            break;
        }
        break;
        
      case "same_as":
        if (isset($arrFields[$strFieldName]) && isset($arrFields[$strFieldName2]) && $arrFields[$strFieldName] != $arrFields[$strFieldName2])
          $arrErrors[$strFieldName] = $strErrorMessage;
        break;

      case "valid_date":
        // this is written for future extensibility of isValidDate function to allow 
        // checking for dates BEFORE today, AFTER today, IS today and ANY day.
        $blnIsLaterDate = false;
        if    ($blnDateFlag == "later_date")
          $blnIsLaterDate = true;
        else if ($blnDateFlag == "any_date")
          $blnIsLaterDate = false;

        if (isset($arrFields[$strFieldName]) && isset($arrFields[$strFieldName2]) && isset($arrFields[$strFieldName3]) && !isValidDate($arrFields[$strFieldName], $arrFields[$strFieldName2], $arrFields[$strFieldName3], $blnIsLaterDate))
          $arrErrors[$strFieldName] = $strErrorMessage;
        break;

      case "is_alpha":
        if (isset($arrFields[$strFieldName]) && preg_match('/[^A-Za-z0-9]/', $arrFields[$strFieldName]))
          $arrErrors[$strFieldName] = $strErrorMessage; 
        break;
        
      case "custom_alpha":
        $arrChars = array();
        $arrChars["L"] = "[A-Z]";
        $arrChars["V"] = "[AEIOU]";
        $arrChars["l"] = "[a-z]";
        $arrChars["v"] = "[aeiou]";
        $arrChars["D"] = "[a-zA-Z]";
        $arrChars["F"] = "[aeiouAEIOU]";
        $arrChars["C"] = "[BCDFGHJKLMNPQRSTVWXYZ]";
        $arrChars["x"] = "[0-9]";
        $arrChars["c"] = "[bcdfghjklmnpqrstvwxyz]";
        $arrChars["X"] = "[1-9]";
        $arrChars["E"] = "[bcdfghjklmnpqrstvwxyzBCDFGHJKLMNPQRSTVWXYZ]";

        $strRegExpStr = "";
        for ($intCount2=0; $intCount2<strlen($strFieldName2); $intCount2++)
        {
          if (array_key_exists($strFieldName2[$intCount2], $arrChars))
            $strRegExpStr .= $arrChars[$strFieldName2[$intCount2]];
          else
            $strRegExpStr .= $strFieldName2[$intCount2];
        }

        if (isset($arrFields[$strFieldName]) && !empty($arrFields[$strFieldName]) && !preg_match("/$strRegExpStr/", $arrFields[$strFieldName]))
          $arrErrors[$strFieldName] = $strErrorMessage;
        break;

      case "reg_exp":
        $strRegExpStr = $strFieldName2;

        // rather crumby, but...
        if (count($strRow) == 5)
          $strRegExp = "/" . $strRegExpStr . "/" . $strRow[3]; 
        else
          $strRegExp = "/" . $strRegExpStr . "/"; 

        if (isset($arrFields[$strFieldName]) && !empty($arrFields[$strFieldName]) && !preg_match($strRegExp, $arrFields[$strFieldName]))
          $arrErrors[$strFieldName] = $strErrorMessage;
        break;
      case "function":
	  $strFunction = $strRow[2];
	  if(isset($arrFields[$strFieldName])){
		  $blnResult = $objModel->$strFunction($arrFields[$strFieldName]);
		  if($blnResult==false)
			$arrErrors[$strFieldName] = $strErrorMessage;
	  }
	  break;
      default:
        die("Unknown requirement flag in validate_fields(): $strRequirement");
        break;
    }
  }
  
  return $arrErrors;
}


/*------------------------------------------------------------------------------------------------*\
  Function:   isValidDate
  Purpose:    checks a date is valid / is later than current date
  Parameters: $intMonth       - an integer between 1 and 12
              $intDay         - an integer between 1 and 31 (depending on month)
              $intYear        - a 4-digit integer value
              $blnIsLaterDate - a boolean value. If true, the function verifies the date being passed 
                               in is LATER than the current date.
\*------------------------------------------------------------------------------------------------*/
function isValidDate($intMonth, $intDay, $intYear, $blnIsLaterDate)
{
  // depending on the year, calculate the number of days in the month
  if ($intYear % 4 == 0)      // LEAP YEAR 
    $arrDaysInMonth = array(31, 29, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31);
  else
    $arrDaysInMonth = array(31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31);


  // first, check the incoming month and year are valid. 
  if (!$intMonth || !$intDay || !$intYear) return false;
  if (1 > $intMonth || $intMonth > 12)  return false;
  if ($intYear < 0)                  return false;
  if (1 > $intDay || $intDay > $arrDaysInMonth[$intMonth-1]) return false;


  // if required, verify the incoming date is LATER than the current date.
  if ($blnIsLaterDate)
  {    
    // get current date
    $strToday = date("U");
    $strDate = mktime(0, 0, 0, $intMonth, $intDay, $intYear);
    if ($strDate < $strToday)
      return false;
  }

  return true;
}

}
?>
