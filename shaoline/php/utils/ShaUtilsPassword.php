<?php

/**
 * Construct ShaUtilsPassword object
 *
 * @category   Classes
 * @package    IamBundle
 * @copyright  Copyright (c) 2014 CGI (http://www.cgi.com)
 * @license    http://opensource.org/licenses/gpl-license.php GNU Public License
 * @author     Bastien DUHOT <bastien.duhot@cgi.com>
 * @version    1.0
 */
class ShaUtilsPassword
{

    static private $_defaultSize = 8;
    static private $_defaultLowerQty = 1; 
    static private $_defaultUpperQty = 1;
    static private $_defaultNumericQty = 1;
    static private $_defaultSpecialQty = 0;
    
    /**
     * Rules
     */
    private $_qtyLowercaseMin;
    private $_qtyUppercaseMin;
    private $_qtyNumberMin;
    private $_qtySpecialCharMin;
    private $_qtyCharMin;
    private $_wordProhibed;

    /**
     * Regex
     */
    private $_lowerCaseChar;
    private $_upperCaseChar;
    private $_numberCaseChar;

    /**
     * Current errors
     */
    private $_currentErrors;

    /**
     * Default constructor
     *
     * @param array $options Security rules
     *   - qty_lowercase_min (int) => Min qty of lowercase char wanted in your password
     *   - qty_uppercase_min (int) => Min qty of uppercase char wanted in your password
     *   - qty_number_min (int) => Min qty of number char wanted in your password
     *   - qty_special_char_min (int) => Min qty of special char in your password
     *   - qty_char_min (int) => Min qty of char in your password
     *   - words_prohibed (json) => black listed words
    */
    public function __construct($options = array())
    {
        
        //Save parameters
        $this->_qtyLowercaseMin   = (isset($options['qty_lowercase_min'])) ? $options['qty_lowercase_min'] : self::$_defaultLowerQty;
        $this->_qtyUppercaseMin   = (isset($options['qty_uppercase_min'])) ? $options['qty_uppercase_min'] : self::$_defaultUpperQty;
        $this->_qtyNumberMin      = (isset($options['qty_number_min'])) ? $options['qty_number_min'] : self::$_defaultNumericQty;
        $this->_qtySpecialCharMin = (isset($options['qty_special_char_min'])) ? $options['qty_special_char_min'] : self::$_defaultSpecialQty;
        $this->_qtyCharMin        = (isset($options['qty_char_min'])) ? $options['qty_char_min'] : self::$_defaultSize;
        $this->_wordProhibed      = (isset($options['words_prohibed'])) ?
            $options['words_prohibed'] : array("", "12345", "azerty", "querty");

        $totalCharMin
                           =
            $this->_qtyLowercaseMin + $this->_qtyUppercaseMin + $this->_qtyNumberMin + $this->_qtySpecialCharMin;
        $this->_qtyCharMin = max($this->_qtyCharMin, $totalCharMin);

        //Define chars
        $this->_lowerCaseChar  = "/[a-z]/";
        $this->_upperCaseChar  = "/[A-Z]/";
        $this->_numberCaseChar = "/[0-9]/";

        //Init error array
        $this->_currentErrors = array();
        $this->_clearErrors();

    }

    /**
     * Clear instance error array
     */
    private function _clearErrors()
    {
        $this->_currentErrors['MISSING_LOWER']   = false;
        $this->_currentErrors['MISSING_UPPER']   = false;
        $this->_currentErrors['MISSING_NUMBER']  = false;
        $this->_currentErrors['MISSING_SPECIAL'] = false;
        $this->_currentErrors['MISSING_CHAR']    = false;
        $this->_currentErrors['BLACK_LISTED']    = false;
    }

    /**
     * Return true if password have got an error
     * (you must call 'getPasswordSecurityLevel' function before to calculate errors)
     *
     * @return bool
     */
    private function _haveError()
    {
        return (
            $this->_currentErrors['MISSING_LOWER']
            || $this->_currentErrors['MISSING_UPPER']
            || $this->_currentErrors['MISSING_NUMBER']
            || $this->_currentErrors['MISSING_SPECIAL']
            || $this->_currentErrors['MISSING_CHAR']
            || $this->_currentErrors['BLACK_LISTED']
        );
    }

    /**
     * Get password secutity level
     * Fill $this->currentErrors with erros found
     *
     * @param string $password Password value
     *
     * @returns int
     * @private
     */
    private function _checkErrors($password)
    {

        //Clear all previous errors
        $this->_clearErrors();

        $qtyLower   = 0;
        $qtyUpper   = 0;
        $qtyNumber  = 0;
        $qtySpecial = 0;

        $passwordLength = strlen($password);
        for ($i = 0; $i < $passwordLength; $i++) {
            $char = $password[$i];
            if (preg_match($this->_lowerCaseChar, $char)) {
                $qtyLower++;
            } elseif (preg_match($this->_upperCaseChar, $char)) {
                $qtyUpper++;
            } elseif (preg_match($this->_numberCaseChar, $char)) {
                $qtyNumber++;
            } else {
                $qtySpecial++;
            }
        }

        if ($qtyLower < $this->_qtyLowercaseMin) {
            $this->_currentErrors['MISSING_LOWER'] = true;
        }
        if ($qtyUpper < $this->_qtyUppercaseMin) {
            $this->_currentErrors['MISSING_UPPER'] = true;
        }
        if ($qtyNumber < $this->_qtyNumberMin) {
            $this->_currentErrors['MISSING_NUMBER'] = true;
        }
        if ($qtySpecial < $this->_qtySpecialCharMin) {
            $this->_currentErrors['MISSING_SPECIAL'] = true;
        }
        if ($passwordLength < $this->_qtyCharMin) {
            $this->_currentErrors['MISSING_CHAR'] = true;
        }

        if (in_array($password, $this->_wordProhibed)) {
            $this->_currentErrors['BLACK_LISTED'] = true;
        }

    }

    /**
     * isValidePassword
     *
     * @param string $password Password to check
     *
     * @return bool
     */
    public function isValidePassword($password)
    {
        $this->_checkErrors($password);

        return !$this->_haveError();
    }

    /**
     * Generate random password based on policies
     *
     * @return string
     */
    public function generateRandomPassword()
    {
        $password = "";

        $alphabets = array(
            'special' => "!?$*&#|_-=",
            'digit'   => "0123456789",
            'upper'   => "ABCDEFGHIJKLMNOPQRSTUVWXYZ",
            'lower'   => "abcdefghijklmnopqrstuwxyz",
        );

        //Calculate random length based on policy min length
        $randomPassLength = $this->_qtyCharMin + rand(0, 3);
        $minPassLength    = $this->_qtySpecialCharMin
            + $this->_qtyNumberMin
            + $this->_qtyUppercaseMin
            + $this->_qtyLowercaseMin;

        //Calculate max random length
        $maxRand = intval(($randomPassLength - ($minPassLength)) / 4);

        //Calculate length for char group based on their min length
        $tabLength            = array();
        $tabLength['special'] = $this->_qtySpecialCharMin + rand(0, $maxRand);
        $tabLength['digit']   = $this->_qtyNumberMin + rand(0, $maxRand);
        $tabLength['upper']   = $this->_qtyNumberMin + rand(0, $maxRand);
        $tabLength['lower']   = $randomPassLength - (array_sum($tabLength));

        foreach ($tabLength as $charType => $charLength) {
            $password .= substr(str_shuffle($alphabets[$charType]), 0, $charLength);
        }

        //shuffle password chars
        $password = str_shuffle($password);

        return $password;
    }


}
