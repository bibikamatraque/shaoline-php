<?php

/**
 * Class ShaBddField
 * This class define structure SQL ShaBddField
 *
 * @category   ORM
 * @package    Shaoline
 * @copyright  Copyright (c) 2014 Bastien DUHOT (bastien.duho@free.fr)
 * @license    Contact bastien.duho@free.fr
 * @author     Bastien DUHOT <Contact bastien.duho@free.fr>
 * @version    1.0
 */
class ShaBddField
{

    /**************/
    /* ATTRIBUTES */
    /**************/

    /** @var ShaBddTable $parent Parent table */
    private $_parent;
    /** @var string $_name ShaBddField name */
	private $_name;
    /** @var string $_type ShaBddField type name */
    private $_type;
    /** @var string $_normalType ShaBddField type normalized name */
    private $_normalType;
    /** @var bool $_primary Is primary field */
    private $_isPrimary = false;
	/** @var bool $_isNullable Define is field can be null */
    private $_isNullable = false;
	/** @var string $_default Define default value */
    private $_default = null;
	/** @var bool $_isAutoValue Define if field has auto value */
    private $_isAutoValue = false;
    /** @var  bool $_isAutoIncremental Define if fiueld is autoincrement mode */
    private $_isAutoIncremental = false;
    /** @var  string $_comment Comment */
    private $_comment = "";
    /** @var  string $_collate Collate */
    private $_collate = "";
    /** @var  string $_size Size */
    private $_size = 50;
	
    /***********************/
    /* SETTERS AND GETTERS */
    /***********************/

    /**
     * Return true if field can be null
     *
     * @return boolean
     */
    public function isNullable()
    {
        return $this->_isNullable;
    }

    /**
     * Define field nullable mode
     *
     * @param boolean $isNullable
     *
     * @return ShaBddField
     */
    public function setNullable($isNullable)
    {
        $this->_isNullable = $isNullable;
        $this->_autoSetDefaultValue();
        return $this;
    }

	 /**
     * Define field nullable mode
     *
     * @param boolean $isNullable
     *
     * @return ShaBddField
     */
    public function setSize($size)
    {
        $_size = $size;
    }

	 /**
     * Return field size
     *
     * @return int
     */
    public function getSize()
    {
        return $_size;
    }
	
    /**
     * Return default value
     *
     * @return string
     */
    public function getDefault()
    {
        return $this->_default;
    }

    /**
     * Define default value
     *
     * @param string $default
     *
     * @return ShaBddField
     */
    public function setDefault($default)
    {
        $this->_default = $default;
        if (strcmp(strtoupper($default), "CURRENT_TIMESTAMP") == 0){
            $this->_isAutoValue = true;
        }
        return $this;
    }

    /**
     * Return true if field is autoincrement
     *
     * @return boolean
     */
    public function isAutoValue()
    {
        return $this->_isAutoValue;
    }

    /**
     * Define autincrement mode
     *
     * @param boolean $isAutoValue
     *
     * @return ShaBddField
     */
    public function setAutoValue($isAutoValue)
    {
        $this->_isAutoValue = $isAutoValue;
        return $this;
    }

    /**
     * Return field name
     *
     * @return string
     */
    public function getName()
    {
        return $this->_name;
    }

    /**
     * Define field name
     *
     * @param string $name
     *
     * @return ShaBddField
     */
    public function setName($name)
    {
        $this->_name = $name;
        return $this;
    }

    /**
     * Return field normalise type (TEXT, UNSIGNEDINT, INT, UNSIGNEDDECIMAL, DECIMAL, DATETIME, TIMESTAMP, DATE)
     *
     * @return string
     */
    public function getType()
    {
        return $this->_type;
    }

    /**
     * Return field normalise type (TEXT, UNSIGNEDINT, INT, UNSIGNEDDECIMAL, DECIMAL, DATETIME, TIMESTAMP, DATE)
     *
     * @return string
     */
    public function getNormalizedType()
    {
        return $this->_normalType;
    }

    /**
     * Define field type
     *
     * @param string $type
     *
     * @return ShaBddField
     */
    public function setType($type)
    {
        $this->_type = $type;
        $this->_normalType = $this->_normalizeType($type);
        $this->_autoSetDefaultValue();
        return $this;
    }

    /**
     * Create reference to other table (using current field)
     *
     * @param $class
     *
     * @return $this
     */
    public function refereTo($class){
        $this->_parent->addReference($class)
            ->setType("1<1")
            ->setTo($class)->using($this->_name)
        ;
        return $this;
    }

    /**
     * Return true if field is autoinrement mode
     *
     * @return boolean
     */
    public function isAutoIncremental()
    {
        return $this->_isAutoIncremental;
    }

    /**
     * Define if field is autoincrement
     *
     * @return ShaBddField
     */
    public function setAutoIncremental()
    {
        $this->_isAutoIncremental = true;
        $this->_isPrimary = true;
        $this->_isAutoValue = true;
		$this->_default = null;
		$this->_isNullable = false;
        $this->_parent->setAutoIncremental(true);
        return $this;
    }

    /**
     * Return true if field is a primary key
     *
     * @return boolean
     */
    public function isPrimary()
    {
        return $this->_isPrimary;
    }

    /**
     * Define if field is primary key
     *
     * @return ShaBddField
     */
    public function setPrimary()
    {
        $this->_isPrimary = true;
        return $this;
    }

    /**
     * @return string
     */
    public function getComment()
    {
        return $this->_comment;
    }

    /**
     * @param string $comment
     *
     * @return ShaBddField
     */
    public function setComment($comment)
    {
        $this->_comment = $comment;
        return $this;
    }

    /**
     * @return string
     */
    public function getCollate()
    {
        return $this->_collate;
    }

    /**
     * @param string $collate
     */
    public function setCollate($collate)
    {
        $this->_collate = $collate;
    }

    /**
     * Index field
     *
     * @param $type
     *
     * @return ShaBddField
     */
    public function setIndex($type = ""){
        $this->_parent->addIndex($this->getName(), $type);
        return $this;
    }


    /**********************/
    /* SPECIFIC FUNCTIONS */
    /**********************/

    /**
     * Simple constructor
     *
     * @param ShaBddTable $parent Parent table
     */
    function __construct($parent){
        $this->_parent = $parent;
    }

    /**
     * Init all datas
     *
     * @param string $type ShaBddField type
     * @param string $primary Is primary key
     * @param string $isNullable Is Null allowed
     * @param string $default Default value
     * @param string $auto Is autoincrement field
     *
     * @return ShaBddField
     * @throws Exception
     */
    public function init($type, $primary, $isNullable, $default, $auto) {

        $type = strtolower($type);
        $primary = strtolower($primary);
        $isNullable = strtolower($isNullable);
        $default = strtolower($default);
        $auto = strtolower($auto);

        try {
            $this->_type = $type;
            $this->_normalType = $this->_normalizeType($type);
            if ($this->_normalType == "TEXT"){
                $this->_collate = "utf8_general_ci";
            }
            $this->_isPrimary = ($primary == "pri");
            $this->_isNullable = ($isNullable == "yes" && $type != "text");
            $this->_default = ($type != "text") ? $default : null;
            $this->_isAutoValue = ($auto == "auto_increment") || ($auto == "CURRENT_TIMESTAMP");
            $this->_isAutoIncremental = ($auto == "auto_increment");
            $this->_autoSetDefaultValue();
            return $this;
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    /**
     * Convert SQL type to ShaDao type
     *
     * @param string $sqlType SQL field type
     *
     * @return string ShaDao field type
     * @throws Exception Error description
     */
    private function _normalizeType($sqlType) {

        $sqlType = strtolower($sqlType);

        if (strpos($sqlType, "text") > -1 || strpos($sqlType, "varchar") > -1) {
            return "TEXT";
        } else if (strpos($sqlType, "int") > -1 && strpos($sqlType, "unsigned") > -1) {
            return "UNSIGNEDINT";
        } else if (strpos($sqlType, "int") > -1) {
            return "INT";
        } else if ((strpos($sqlType, "float") > -1 || strpos($sqlType, "double") > -1 || strpos($sqlType, "decimal") > -1) && (strpos($sqlType, "unsigned") > -1)) {
            return "UNSIGNEDDECIMAL";
        } else if (strpos($sqlType, "float") > -1 || strpos($sqlType, "double") > -1 || strpos($sqlType, "decimal") > -1) {
            return "DECIMAL";
        } else if (strpos($sqlType, "datetime") > -1 || strpos($sqlType, "timestamp") > -1) {
            return "DATETIME";
        } else if (strpos($sqlType, "date") > -1) {
            return "DATE";
        } else {
            throw new Exception("Type de champ inconnu (" . $sqlType . ")");
        }
    }

    /**
     * Return an estimated default value
     *
     * @return string
     */
    public function determineDefaultValue(){

        if ($this->_normalType == "TEXT") {
            return '';
        } else if ($this->_normalType == "UNSIGNEDINT") {
            return '0';
        } else if ($this->_normalType == "INT") {
            return '0';
        } else if ($this->_normalType == "UNSIGNEDDECIMAL") {
            return '0.00';
        } else if ($this->_normalType == "DECIMAL") {
            return '0.00';
        } else if ($this->_normalType == "DATETIME" || $this->_normalType == "TIMESTAMP") {
            return '0000-00-00 00:00:00';
        } else if ($this->_normalType == "DATE") {
            return '0000-00-00';
        }
        return "";
    }

	/**
	 * Set forced default value
	 */
	private function _autoSetDefaultValue(){

		if (!$this->_isPrimary  && !isset($this->_default) && !$this->_isNullable) {
            $this->_default = $this->determineDefaultValue();
		}	
		if (strtolower($this->_type) == "text" || $this->_isAutoValue){
			$this->_default  = null;
			$this->_isNullable = true;
		}
	}

    /**
     * Return if field type need quotes
     * @return bool
     */
    public function needQuote(){
        return (
            $this->_normalType == "TEXT" ||
            $this->_normalType == "DATETIME" ||
            $this->_normalType == "TIMESTAMP" ||
            $this->_normalType == "DATE"
        );
    }

	/**
	 * Return init value
	 *
	 * @return string
	 */
	public function getInitValue(){

		if ($this->_isAutoValue) {
			return null;
		}
		if ($this->isAutoDate()) {
			return null;
		}
        if (isset($this->_default)){
            return $this->_default;
        } else {
            return $this->determineDefaultValue();
        }
	}

	/**
	 * Return true if field is aurto date value
	 */
	public function isAutoDate(){
	    return (strcmp("CURRENT_TIMESTAMP", $this->_default) == 0) ;
	}

	/**
	 * Check the integrity of value
	 *
	 * @param string $value Value to test
	 *
	 * @return boolean Ture if the value is allowed for this kind of field
	 * @throws Exception Error description
	 */
	public function check($value) {
		
		if (is_object($value) || is_array($value)) {
			throw new Exception(
                __CLASS__."::".__FUNCTION__." : ShaBddField cannot be an object or an array !"
            );
		}

		if ($this->_normalType == "TEXT") {
			return true; //(isset($value));
		} else if ($this->_normalType == "UNSIGNEDINT") {
			return (isset($value) && ShaUtilsString::isRegexInteger($value) && $value >= 0);
		} else if ($this->_normalType == "INT") {
			return (isset($value) && ShaUtilsString::isRegexInteger($value));
		} else if ($this->_normalType == "UNSIGNEDDECIMAL") {
			return (isset($value) && ShaUtilsString::isRegexDecimal($value) && $value >= 0);
		} else if ($this->_normalType == "DECIMAL") {
			return (isset($value) && ShaUtilsString::isRegexDecimal($value));
		} else if ($this->_normalType == "DATETIME" || $this->_normalType == "TIMESTAMP") {
			if ($this->_normalType == "TIMESTAMP" && $this->_isAutoValue && $value="") {
				return true;
			}
			return (isset($value) && ShaUtilsString::isRegexDatetime($value));
		} else if ($this->_normalType == "DATE") {
			return (isset($value) && ShaUtilsString::isRegexDate($value));
		} else {
			throw new Exception(
                __CLASS__."::".__FUNCTION__." : Bdd field type unknow : (" . $this->_normalType . ")"
            );
		}
	}

	/**
	 * Return true if field is int or unsigned int type
	 *
	 * @return boolean
	 */
	public function canBeIncremented(){
		return ($this->_normalType == "UNSIGNEDINT") || ($this->_normalType == "INT") ;
	}



	/**
	 * Transform value with modification necessary for this kind of field
	 *
	 * @param string $value Value to transform
	 *
	 * @return string
	 */
	public function formatType($value, $addCollation = false) {

        if ($this->isAutoValue() && strtoupper($value) == "CURRENT_TIMESTAMP"){
            return "CURRENT_TIMESTAMP";
        }

        $collation = "";
        if ($addCollation && $this->getNormalizedType() == "TEXT"){
            $collation = " collate utf8_bin ";
        }

		return ($this->needQuote()) ? "'" . ShaUtilsString::cleanForSQL($value) . "' $collation" : ShaUtilsString::cleanForSQL($value).$collation;
	}

    /**
     * @return ShaBddTable
     */
    public function end(){
        return $this->_parent;
    }

}

?>