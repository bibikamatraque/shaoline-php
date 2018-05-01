<?php


class ShaFormField extends ShaChildSerializable
{

    /*************/
    /* CONSTANTS */
    /*************/

    //Displaying types
    const RENDER_TYPE_TEXT = 0;
    const RENDER_TYPE_PICTURE = 1;
    const RENDER_TYPE_PASSWORD = 2;
    const RENDER_TYPE_TEXTAREA = 3;
    const RENDER_TYPE_RADIOBOX = 4;
    const RENDER_TYPE_CHECKBOX = 5;
    const RENDER_TYPE_COMBOBOX = 6;
    const RENDER_TYPE_CHECKBOX_LIST = 7;
    const RENDER_TYPE_RADIOBOX_LIST = 8;
    const RENDER_TYPE_SUBMIT = 9;
    const RENDER_TYPE_SWITCHVALUE = 10;
    const RENDER_TYPE_SWITCHPICTURE = 11;
    const RENDER_TYPE_CAPCHA=12;
    const RENDER_TYPE_DATE=13;
    const RENDER_TYPE_DB_COMBOBOX=14;
    const RENDER_HIDDEN=15;


    /**************/
    /* ATTRIBUTES */
    /**************/

    /** @var ShaForm Parent form */
    private $_parent;
    /** @var int internal id (DEFAULT = auto) */
    protected $_id = null;
    /** @var string Name of dao field (DEFAULT  = null) */
    protected $_daoField = null;
    /** @var  string Wanted input format (DEFAULT = ShaUtilsString::PATTERN_ALL) */
    protected $_format = ShaUtilsString::PATTERN_ALL;
    /** @var  int Render type (DEFAULT = ShaFormField::RENDER_TYPE_TEXT) */
    protected $_renderer = self::RENDER_TYPE_TEXT;
    /** @var  string Empty text (DEFAULT = "") */
    protected $_placeholder = "";
    /** @var  string Class css (DEFAULT = "") */
    protected $_cssClass = "";
    /** @var  string Class css (DEFAULT = "") */
    protected $_inputCssClass = "";
    /** @var int Max value length (DEFAULT = 250) */
    protected $_maxLength = 250;
    /** @var int Min value length (DEFAULT = 0) */
    protected $_minLength = 0;
    /** @var int Max value (DEFAULT = null) */
    protected $_maxValue = null;
    /** @var int Min value (DEFAULT = null) */
    protected $_minValue = null;
    /** @var  bool Enable/Disable lib (DEFAULT = true) */
    protected $_libEnable = true;
    /** @var  string ShaBddField lib (DEFAULT = "") */
    protected $_lib = "";
    /** @var  bool Enable/Disable input (DEFAULT = true) */
    protected $_inputEnable = true;
    /** @var string Type of crypt methode used before save (DEFAULT = null) */
    protected $_crypt = null;
    /** @var  string Type of uncrypt methode used before use (DEFAULT = null) */
    protected $_uncrypt = null;
    /** @var bool If true, field must be equal to previous field (DEFAULT = false) */
    protected $_bis = false;
    /** @var array Associative array width key/value, used for list type input (DEFAULT = null) */
    protected $_datas = null;
    /** @var string Starting value (DEFAULT = "") */
    protected $_value = "";
    /** @var string Submited value (DEFAULT = null)*/
    protected $_submitValue = null;
    /** @var bool Value will be save only if not empty (DEFAULT = false) */
    protected $_saveOnlyIfNotEmpty = false;
    /** @var bool Define if value can be empty or not (DEFAULT = false) */
    protected $_allowEmpty = false;
    /** @var  bool $_isEditable Define if field is editable or not (DEFAULT = true) */
    protected $_isEditable = true;
    /** @var  array $_jsEvents Array of JS actions (event/function) (DEFAULT = array())*/
    protected $_jsEvents = array();
    /** @var string $_title Html title attribute (DEFAULT = "")*/
    protected $_title = "";
    /** @var array $_renderDatas Array of index/datas used for switch type render (picture or value) */
    protected $_renderDatas = array();
    /** @var array $_capcha Capcha config */
    protected $_capcha = null;
    /** @var int $_width File witdh*/
    protected $_width = null;
    /** @var int $_height File height*/
    protected $_height = null;
    /** @var string relation */
    protected $_relation = null;
    /** @var string relation */
    protected $_textAlign = "";
	/** @var array $_notIn */
	protected $_notIn = array();
	/** @var array $_in */
	protected $_in = array();
	/** @var array $_fill */
	protected $_autoFill = null;
    /** @var array $_fill */
	protected $_autoFilled = null;
	/** @var bool $_isRsaProtected */
	protected $_isRsaProtected = false;
	/** @var bool $_isMultipleSelection */
	protected $_isMultipleSelection = false;
    /** @var int $_fileMaxSize Max file size (bytes) */
    protected $_fileMaxSize = 500000;
    protected $_fileTypeAllowed = array();
	/** @var int $_index */
	protected $_index = -1;
	/** @var int $_index */
	protected $_checker = array();
	/** @var bool $_index */
	protected $_blankMode = false;
	/** @var array $_index */
	protected $_datePickerParams = null;
	/** @var array $_internalGcIds	 */
	private $_internalGcIds = array();
	/** @var array $_additionalJs */
	private $_additionalJs = array();
	/** @var array $_complexity */
	public $_complexity = null;

	public function toString($glue = PHP_EOL) {
		echo "######################################".$glue;
		$attributes = get_object_vars($this);
		foreach ($attributes as $key => $value) {
			if ($key != "_parent"){
				if (is_object($value) || is_array($value)) {
					var_dump($value);
					echo PHP_EOL;
				} else {
					echo $key."=".$value.$glue;
				}
			}	
		}
		echo "######################################".$glue;
	}
	
	/**
	 * Define field has RSA protected
	 * 
	 * @return ShaFormField;
	 */
	public function setRsaProtected(){
		$this->_isRsaProtected = true;
		return $this;
	}
	
	public function isRsaPretected(){
		return $this->_isRsaProtected;
	}
	
    public function setParent($parent){
        $this->_parent = $parent;
    }

	/**
	 * Define datepicker jqeury attribute
	 * 
	 * @param array $params
	 *     'dateFormat' 	=> 'yy-mm-dd',
     *     'changeYear' 	=> true,
     *     'changeMonth' 	=> true,
	 * 
	 * @return ShaFormField;
	 */
	public function setDatePickerParams($params){
		$this->_datePickerParams = $params;
		return $this;
	}

	
	public function setBlankMode($value){
		$this->_blankMode = $value;
	}
	
	public function getComplexity(){
		return $this->_complexity;
	}
	
	/**
	 * Set complexity
	 * 
	 * @param array $complexity
	 * 
	 *  - qty_lowercase_min
	 *  - qty_uppercase_min
	 *  - qty_number_min
	 *  - qty_special_char_min
	 *  - qty_char_min
	 *  - words_prohibed
	 * 
	 * @return ShaFormField
	 */
	public function setComplexity($complexity){
		$this->_complexity = $complexity;
		return $this;
	}
	
	/**
	 * Get additional JS to apply after form and needed by field 
	 */
	public function getAdditionalJs(){
		return $this->_additionalJs;
	}
	
	/**
	 * @return string
	 *
	 * @return ShaFormField
	 */
	public function addChecker($type, $value, $msg)
	{
		$this->_checker[] = array(
		    'type' => $type,
		    'value' => $value,
		    'msg' => $msg
		);
		return $this;
	}

	/**
	 * @param string $crypt
	 *
	 * @return ShaFormField
	 */
	public function hasChecker()
	{
		return $this->_checker;
	}


	/**
	 *
	 * @return ShaFormField
	 */
	public function addBisChecker()
	{
		$this->_bisChecker = true;
		return $this;
	}

	/**
	 * @param string $crypt
	 *
	 * @return ShaFormField
	 */
	public function hasBisChecker()
	{
		return $this->_bisChecker;
	}

	/**
	 * @return string
	 */
	public function getIndex()
	{
		return $this->_index;
	}

	/**
	 * @param string $crypt
	 *
	 * @return ShaFormField
	 */
	public function setIndex($index)
	{
		$this->_index = $index;
		return $this;
	}

    /**
     * @return boolean
     */
    public function isAllowEmpty()
    {
        return $this->_allowEmpty;
    }

    /**
     * @param boolean $allowEmpty
     *
     * @return ShaFormField
     */
    public function setAllowEmpty($allowEmpty)
    {
        $this->_allowEmpty = $allowEmpty;
        return $this;
    }

    /**
     * @return boolean
     */
    public function isBis()
    {
        return $this->_bis;
    }

    /**
     * @param boolean $bis
     *
     * @return ShaFormField
     */
    public function setBis($field)
    {
        $this->addChecker(
            'bis',
            $this->_parent->getDomId()."_".$field,
            ShaContext::t("two_fields_must_be_similar")
        );
        $field = $this->_parent->getField($field);
        $field->addJsEvent("onchange", "\$j('#".$this->_parent->getDomId()."_".$this->getId()."').change()");

        $this->_bis = true;
        return $this;

    }

    /**
     * @return string
     */
    public function getCrypt()
    {
        return $this->_crypt;
    }

    /**
     * @param string $crypt
     *
     * @return ShaFormField
     */
    public function setCrypt($crypt)
    {
        $this->_crypt = $crypt;
        return $this;
    }

    /**
     * @return string
     */
    public function getCssClass()
    {
        return $this->_cssClass;
    }

    /**
     * @param string $cssClass
     *
     * @return ShaFormField
     */
    public function setCssClass($cssClass)
    {
        $this->_cssClass = $cssClass;
        return $this;
    }


    /**
     * @return string
     */
    public function getInputCssClass()
    {
        return $this->_inputCssClass;
    }

    /**
     * @param string $inputCssClass
     *
     * @return ShaFormField
     */
    public function setInputCssClass($inputCssClass)
    {
        $this->_inputCssClass = $inputCssClass;
        return $this;
    }

    /**
     * @return string
     */
    public function getDaoField()
    {
        return $this->_daoField;
    }

    /**
     * @param string $daoField
     *
     * @return ShaFormField
     */
    public function setDaoField($daoField)
    {
        $this->_daoField = $daoField;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getFileMaxSize()
    {
        return $this->_fileMaxSize;
    }

    /**
     * @param mixed $fileMaxSize
     *
     * @return ShaFormField
     */
    public function setFileMaxSize($fileMaxSize)
    {
        $this->_fileMaxSize = $fileMaxSize;
        return $this;
    }

    public function isImageTypeAllowed($type){
        return in_array($type, $this->_fileTypeAllowed);
    }

    /**
     * @param mixed $fileMaxSize
     *
     * @return ShaFormField
     */
    public function setFileTypeAllowed($types){
        if (!is_array($types)){
            $types = array($types);
        }
        $this->_fileTypeAllowed = $types;
        return $this;
    }

    /**
     * @return array
     */
    public function getDatas()
    {
        return $this->_datas;
    }

    /**
     * @param array $datas
     *
     * @return ShaFormField
     */
    public function setDatas($datas)
    {
        $this->_datas = $datas;
        return $this;
    }

    /**
     * @return string
     */
    public function getFormat()
    {
        return $this->_format;
    }

    /**
     * @param string $format
     *
     * @return ShaFormField
     */
    public function setFormat($format)
    {
        $this->_format = $format;
        return $this;
    }

    /**
     * @return array
     */
    public function getRenderDatas()
    {
        return $this->_renderDatas;
    }

    /**
     * @param array $renderDatas
     *
     * @return ShaFormField
     */
    public function setRenderDatas($renderDatas)
    {
        $this->_renderDatas = $renderDatas;
        return $this;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->_id;
    }

    /**
     * @param int $id
     *
     * @return ShaFormField
     */
    public function setId($id)
    {
        $this->_id = $id;
        return $this;
    }

    /**
     * @return boolean
     */
    public function isInputEnable()
    {
        return $this->_inputEnable;
    }

    /**
     * @param boolean $inputEnable
     *
     * @return ShaFormField
     */
    public function setInputEnable($inputEnable)
    {
        $this->_inputEnable = $inputEnable;
        return $this;
    }

    /**
     * @return array
     */
    public function getCapcha()
    {
        return $this->_capcha;
    }

    /**
     * @return int
     */
    public function getWidth()
    {
        return $this->_width;
    }

    /**
     * @param int $width
     *
     * @return ShaFormField
     */
    public function setWidth($width)
    {
        $this->_width = $width;
        return $this;
    }

    /**
     * @return int
     */
    public function getHeight()
    {
    	return $this->_height;
    }

    /**
     * @param int $width
     *
     * @return ShaFormField
     */
    public function setHeight($height)
    {
    	$this->_height = $height;
    	return $this;
    }

    /**
     * Define Capcha config
     *  capchaLength
     *  capchaValue
     *  capchaWidth
     *  capchaHeight
     *  capchaNoiseDensity
     *  capchaColorBackR
     *  capchaColorBackG
     *  capchaColorBackB
     *  capchaColorFontR
     *  capchaColorFontG
     *  capchaColorFontB
     *  capchaColorNoiseR
     *  capchaColorNoiseG
     *  capchaColorNoiseB
     *
     * @param array $capcha
     *
     * @return ShaFormField
     */
    public function setCapcha($capcha)
    {

        $this->_capcha = $capcha;
        $this->_capcha["capchaValue"] = ShaUtilsCapcha::getRandomCode( $this->_capcha['capchaLength']);
        return $this;
    }

    /**
     * @return boolean
     */
    public function isEditable()
    {
        return $this->_isEditable;
    }

    /**
     * @param boolean $isEditable
     *
     * @return ShaFormField
     */
    public function setEditable($isEditable)
    {
        $this->_isEditable = $isEditable;
        return $this;
    }

    public function setDecorElement(){
    	$this
    		->setEditable(false)
    		->setInputEnable(false)
    		->setLibEnable(false)
    	;
    	return $this;
    }
    
    /**
     * @return array
     */
    public function getJsEvents()
    {
        return $this->_jsEvents;
    }

    /**
     * @param array $jsEvent
     *
     * @return ShaFormField
     */
    public function addJsEvent($event, $jsEvent, $gcIds = null)
    {
        if (!isset($this->_jsEvents[$event])){
            $this->_jsEvents[$event] = array();
        }

        $this->_jsEvents[$event][] = $jsEvent;
        if ($gcIds != null){
            if (is_array($gcIds)){
                $this->_internalGcIds = array_merge($this->_internalGcIds, $gcIds);
            } else {
                $this->_internalGcIds = array_merge($this->_internalGcIds, explode(";", $gcIds));
            }
        }
        return $this;
    }

    /**
     * @return string
     */
    public function getLib()
    {
        return $this->_lib;
    }

    /**
     * @param string $lib
     *
     * @return ShaFormField
     */
    public function setLib($lib)
    {
        $this->_lib = $lib;
        return $this;
    }

    /**
     * @return boolean
     */
    public function isLibEnable()
    {
        return $this->_libEnable;
    }

    /**
     * @param boolean $libEnable
     *
     * @return ShaFormField
     */
    public function setLibEnable($libEnable)
    {
        $this->_libEnable = $libEnable;
        return $this;
    }

    /**
     * @return int
     */
    public function getMaxLength()
    {
        return $this->_maxLength;
    }

    /**
     * @param int $maxLength
     *
     * @return ShaFormField
     */
    public function setMaxLength($maxLength)
    {
        $this->addChecker(
            'maxSize',
            $maxLength,
            ShaContext::tt("field_is_too_long_%0%", $maxLength)
        );
        $this->_maxLength = $maxLength;
        return $this;
    }

    /**
     * @return int
     */
    public function getMaxValue()
    {
        return $this->_maxValue;
    }

    /**
     * @param int $maxValue
     *
     * @return ShaFormField
     */
    public function setMaxValue($maxValue)
    {
        $this->_maxValue = $maxValue;
        return $this;
    }

    /**
     * @return int
     */
    public function getMinLength()
    {
        return $this->_minLength;
    }

    /**
     * @param int $minLength
     *
     * @return ShaFormField
     */
    public function setMinLength($minLength)
    {
        $this->addChecker(
            'minSize',
            $minLength,
            ShaContext::tt("field_is_too_short_%0%", $minLength)
        );
        $this->_minLength = $minLength;
        return $this;
    }

    /**
     * @return int
     */
    public function getMinValue()
    {
        return $this->_minValue;
    }

    /**
     * @param int $minValue
     *
     * @return ShaFormField
     */
    public function setMinValue($minValue)
    {
        $this->_minValue = $minValue;
        return $this;
    }

    /**
     * Define if field must be read during form control
     *
     * @return bool
     */
    public function getMustSubmit()
    {
        return (
            $this->isInputEnable() &&
            $this->isEditable() &&
            $this->getRelation() == null &&
            //$this->getRenderer() != self::RENDER_TYPE_PICTURE &&
            $this->getRenderer() != self::RENDER_TYPE_SUBMIT &&
            $this->getRenderer() != self::RENDER_TYPE_SWITCHPICTURE
        );
    }

    /**
     * @return string
     */
    public function getPlaceholder()
    {
        return $this->_placeholder;
    }

    /**
     * @param string $placeholder
     *
     * @return ShaFormField
     */
    public function setPlaceholder($placeholder)
    {
        $this->_placeholder = $placeholder;
        return $this;
    }

    /**
     * @return string
     */
    public function getTextAlign()
    {
        return $this->_textAlign;
    }

    /**
     * @param string $textAlign
     *
     * @return ShaFormField
     */
    public function setTextAlign($textAlign)
    {
        $this->_textAlign = $textAlign;
        return $this;
    }

    /**
     * @return string
     */
    public function getRenderer()
    {
        return $this->_renderer;
    }

    /**
     * @param string $renderer
     *
     * @return ShaFormField
     */
    public function setRenderer($renderer)
    {
        $this->_renderer = $renderer;
        return $this;
    }

    /**
     * @return boolean
     */
    public function isSaveOnlyIfNotEmpty()
    {
        return $this->_saveOnlyIfNotEmpty;
    }

    /**
     * @param boolean $saveOnlyIfNotEmpty
     *
     * @return ShaFormField
     */
    public function setSaveOnlyIfNotEmpty($saveOnlyIfNotEmpty)
    {
        $this->_saveOnlyIfNotEmpty = $saveOnlyIfNotEmpty;
        return $this;
    }

    /**
     * @return string
     */
    public function getSubmitValue()
    {
        return $this->_submitValue;
    }

    /**
     * @param string $submitValue
     *
     * @return ShaFormField
     */
    public function setSubmitValue($submitValue)
    {
        $this->_submitValue = $submitValue;
        return $this;
    }

    /**
     * @return string
     */
    public function getUncrypt()
    {
        return $this->_uncrypt;
    }

    /**
     * @param string $uncrypt
     *
     * @return ShaFormField
     */
    public function setUncrypt($uncrypt)
    {
        $this->_uncrypt = $uncrypt;
        return $this;
    }

    /**
     * @return string
     */
    public function getValue()
    {
        return $this->_value;
    }

    /**
     * @param string $value
     *
     * @return ShaFormField
     */
    public function setValue($value)
    {
        $this->_value = $value;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getTitle()
    {
        return $this->_title;
    }

    /**
     * @param mixed $title
     *
     * @return ShaFormField
     */
    public function setTitle($title)
    {
        $this->_title = $title;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getRelation()
    {
        return $this->_relation;
    }

    /**
     * @param mixed $relation
     *
     * @return ShaFormField
     */
    public function setRelation($relation)
    {
        $this->_relation = $relation;
        return $this;
    }

    /**
     * Retun true if field can be use in search form
     *
     * @return bool
     */
    public function canBeUseForSearch(){
        return (
            $this->getDaoField() != null &&
            (
                $this->_renderer = ShaFormField::RENDER_TYPE_TEXT ||
                $this->_renderer = ShaFormField::RENDER_TYPE_TEXTAREA
            )
        );
    }

    /**
     * @param mixed $title
     *
     * @return ShaFormField
     */
    public function notIn($daoClass, $bddField, $jsOk, $jsKo)
    {
    	$this->_notIn[] = array('class' => $daoClass, 'field' => $bddField, 'jsOk' => $jsOk, 'jsKo' => $jsKo);
    	return $this;
    }

    /**
     * @return mixed
     */
    public function getNotIn()
    {
    	return $this->_notIn;
    }

    /**
     * @param mixed $title
     *
     * @return ShaFormField
     */
    public function in($daoClass, $bddField, $jsOk, $jsKo)
    {
    	$this->_in[] = array('class' => $daoClass, 'field' => $bddField, 'jsOk' => $jsOk, 'jsKo' => $jsKo);
        return $this;
    }

    /**
     * @return mixed
     */
    public function getIn()
    {
    	return $this->_in;
    }


    public function setMultipleSelection($value){
    	$this->_isMultipleSelection = $value;
    	return $this;
    } 
    
    public function isMultipleSelection(){
    	return $this->_isMultipleSelection;
    }
    
    
    /**
     * Construct in/not int oepration checker
     *
     * @param boolean $isIn
     * @param string $field
     * @param string $daoClass
     * @param string $bddField
     * @param string $jsOk
     * @param string $jsKo
     *
     * @return ShaOperationAction
     */
    private function _addInnerCheckerOperation($isIn, $field, $daoClass, $bddField, $jsOk, $jsKo) {
    	$operation = new ShaOperationAction();
    	$operation
    	->setDaoClass("ShaFormField")
    	->setDaoMethod("innerChecker")
    	->setParameters(
    		array(
    			'isIn' => $isIn,
    			'daoClass' => $daoClass,
    			'bddField' => $bddField,
    			'jsOk' => ShaUtilsString::replace($jsOk, "[CHECKER_ID]", "#form_checker_".$this->_parent->getDomId()."_".$this->getId()),
    			'jsKo' => ShaUtilsString::replace($jsKo, "[CHECKER_ID]", "#form_checker_".$this->_parent->getDomId()."_".$this->getId()),
    		)
    	)
    	->save();

    	return array(
    		$operation->getGcId(),
    		"Shaoline.formFieldChecker(".$operation->getGcId().", '".$this->_parent->getDomId()."_".$this->_parent->getField($field)->getId()."')"
    	);

    }

	/**
	 * Add in and no in operation events
	 */
    public function addInnerCheckerOperations() {
    	foreach ($this->_in as $in) {
    		$operation = $this->_addInnerCheckerOperation(
    			true,
    			$this->getId(),
    			$in['class'],
    			$in['field'],
    			$in['jsOk'],
    			$in['jsKo']
    		);
    		$this->addJsEvent("onchange", $operation[1], $operation[0]);
    	}
    	foreach ($this->_notIn as $notIn) {
    		$operation = $this->_addInnerCheckerOperation(
    			false,
    			$this->getId(),
    			$notIn['class'],
    			$notIn['field'],
    			$notIn['jsOk'],
    			$notIn['jsKo']
    		);
    		$this->addJsEvent("onchange", $operation[1], $operation[0]);
    	}

    	if (count($this->_checker) > 0){
    	    $checker = base64_encode(json_encode($this->_checker));
    	    $this->addJsEvent("onchange", "Shaoline.checkFields('".$this->_parent->getDomId()."_".$this->getId()."', '".$checker."');");
    	}

    	if ($this->_autoFill != null) {
            $autoFillOperation = $this->_getAutoFillOperation();
            $this->addJsEvent("onchange", "Shaoline.doExtendedAction(".$autoFillOperation->getGcId().", '".$autoFillOperation->getEncodedPostParameters()."');", $autoFillOperation->getGcId());
    	}

    	//return ' gcid="'.implode(";", $gcIds).'" onchange="'.implode(";", $jsActions).'" ';
    }

    /**
     * Check in/not in cnstraint and return operation
     *
     * @param array $param
     *     - isIn
     *     - field
     *     - daoClass
     *     - bddField
     *
     * @return ShaResponse
     */
    public static function innerChecker($param) {


    	$response = new ShaResponse();
    	$response->setRenderer(ShaResponse::CONST_RENDERER_NOTHING);

    	try {
    		  $postParameters = ShaUtilsArray::analysePostParameters(array('value' => array('type' => ShaUtilsString::PATTERN_ALL)));
    	} catch (Exception $e) {
    		$response->render();
    		return;
    	}

    	$daoClass = $param['daoClass'];
    	$qty = (int)ShaContext::bddSelectValue("SELECT COUNT(1) FROM ".$daoClass::getTableName()." WHERE ".$param['bddField']." = '".ShaUtilsString::cleanForJs($postParameters['value'])."'");
    	if ( ($param['isIn'] && $qty > 0) || (!$param['isIn'] && $qty == 0) ) {
    		//OK
    		$response->addJsActions($param['jsOk']);
    	}  else {
    		//KO
    		$response->addJsActions($param['jsKo']);
    	}
    	$response->render();
    }

    /**
     * @param mixed $title
     */
    public function setAutoFill($referenceField, $daoObject, $whereClauseField, $idField, $libField)
    {
        $this->_autoFilled =array(
            'referedField' => $referenceField,
            'fieldToFill' => $this->getId(),
            'daoObject' => $daoObject,
            'whereClauseField' => $whereClauseField,
            'idField' => $idField,
            'libField' => $libField
        );

        $field = $this->_parent->getField($referenceField);
        if ($field != null){
            $field->_setAutoFill(
                $this->getId(),
                $daoObject,
                $whereClauseField,
                $idField,
                $libField
            );
        }
    	return $this;
    }

    /**
     * @param mixed $title
     */
    private function _setAutoFill($fieldToFill, $daoObject, $whereClauseField, $idField, $libField)
    {
       $this->_autoFill = array(
            'referedField' => $this->getId(),
            'fieldToFill' => $fieldToFill,
            'daoObject' => $daoObject,
            'whereClauseField' => $whereClauseField,
            'idField' => $idField,
            'libField' => $libField
        );
        return $this;
    }

    private function _getAutoFillOperation() {

        if ($this->_autoFill == null){
            return null;
        }

        $operation = new ShaOperationAction();
        $operation
            ->setDaoClass("ShaFormField")
            ->setDaoMethod("autoFillField")
            ->setParameters(
                array(
                    'referedField' => $this->_parent->getDomId()."_".$this->_autoFill['referedField'],
                    'fieldToFill' => $this->_parent->getDomId()."_".$this->_autoFill['fieldToFill'],
                    'daoObject' => $this->_autoFill['daoObject'],
                    'whereClauseField' => $this->_autoFill['whereClauseField'],
                    'idField' => $this->_autoFill['idField'],
                    'libField' => $this->_autoFill['libField'],
                )
            )
            ->setPostParam(
                array(
                    array(
                        'inputName' => $this->_parent->getDomId()."_".$this->getId(),
                        'paramName' => 'value'
                    )
                )
            )
            ->save()
        ;

        return $operation;
    }

    /**
     * Fill item with other remote
     * @param unknown $param
     */
    public static function autoFillField($param){
        $response = new ShaResponse();
        $response->setRenderer(ShaResponse::CONST_RENDERER_NOTHING);

        try {
            $value = ShaUtilsArray::analysePostParameters(array('value' => array('type' => ShaUtilsString::PATTERN_ALL)));
        } catch (Exception $e) {
            $response->render();
            return;
        }

        $value = ShaUtilsString::cleanForSQL($value['value']);

        $fieldToFill = $param['fieldToFill'];
        $daoObject = $param['daoObject'];
        $whereClauseField = $param['whereClauseField'];
        $idField = $param['idField'];
        $libField = $param['libField'];

        $entries = $daoObject::loadByWhereClause($whereClauseField." = '".$value."'");
        $result = array();
        foreach ($entries as $entry){
            $lib = $entry->getValue($idField);
            $value = $entry->getValue($libField);
            $result[] = array(
                $lib,
                $value
            );
        }

        $response->addJsActions("UtilsForm.fillOptionList('".$fieldToFill."', '".base64_encode(json_encode($result))."', 1);");
        $response->render();
    }


    /**
     * @return mixed
     */
    public function isAutoFilled()
    {
    	return is_array($this->_autoFilled);
    }

    /**
     * Get autofill value allowed for field when referred field is submitted
     * @return multitype:|multitype:unknown
     */
    public function getAutoFillValues($form){

        $referedField = $form->getField($this->_autoFilled['referedField']);
        if ($referedField == null){
            return array();
        }
        $value = $referedField->getSubmitValue();
        $daoObject = $this->_autoFilled['daoObject'];
        $whereClauseField = $this->_autoFilled['whereClauseField'];
        $idField = $this->_autoFilled['idField'];
        $libField = $this->_autoFilled['libField'];

        $entries = $daoObject::loadByWhereClause($whereClauseField." = '".$value."'");
        $result = array();
        foreach ($entries as $entry){
            $lib = $entry->getValue($idField);
            $value = $entry->getValue($libField);
            $result[$lib] = $value;
        }
        return $result;

    }


    /********************/
    /* PUBLIC FUNCTIONS */
    /********************/

    /**
     * Constructor
     *
     * @param ShaForm $parent Parent form
     */
    public function __construct($parent = null){
        $this->_parent = $parent;
    }

    /**
     * @return ShaForm
     */
    public function end(){
        return $this->_parent;
    }

    /**
     * Return html formated field
     *
     * @return string
     * @throws Exception
     */
    public function render(){

        if ($this->getMustSubmit()){
            $name = $this->_parent->getDomId() . "_" . $this->getIndex();
            $id = $this->_parent->getDomId() . "_" . $this->getId();
            $submitable = "form_s_field_". $this->_parent->getDomId();
        } else {
            $name = $this->_parent->getDomId();
            $id = $this->_parent->getDomId();
            $submitable = "";
        }
        
        $this->addInnerCheckerOperations();

        $placeholder = ($this->_placeholder != "") ? " placeholder = '".ShaUtilsString::cleanForJs($this->_placeholder)."' " : "";
        $title = ($this->_title != "") ? " title = '".ShaUtilsString::cleanForJs($this->_title)."' " : "";
        
        $cssStyle = "";
        $cssStyle .= (isset($this->_width)) ? "width:".$this->_width."px;" : "";
        $cssStyle .= (isset($this->_height)) ? "height:".$this->_height."px;" : "";
        $cssStyle = ($cssStyle != "") ? " style='".$cssStyle."' " : "";

        $rsaAttribute = "";
        if (ShaContext::rsaActivated() && $this->_isRsaProtected){
        	$rsaAttribute = " shaRsa ";
        }
        
        $jsEvents = "";
        foreach ($this->_jsEvents as $eventType => $events){
            if (count($events) > 0){
                $jsEvents .= ' ' .$eventType . ' = "' . implode(";", $events). '" ';
            }
        }
        if (count($this->_internalGcIds) > 0){
            $jsEvents .= " gcid='".implode(";", $this->_internalGcIds)."' ";
        }

        $required = (!$this->isAllowEmpty()) ? " required " : "";

        ///// TEXT ////
        if ($this->_renderer == self::RENDER_TYPE_TEXT) {

        	$value = (!is_array($this->_value) && $this->_value!="") ? ' value = "'.ShaUtilsString::quoteDblProtection($this->_value).'" ' : "";
        	
            if ($this->_isEditable) {
                return "
                    <input
                        ".$required."
                        type='text'
                        $cssStyle
                        name='".$name."'
                        id='".$id."'
                        class='$submitable cms_input ".$this->_inputCssClass."'
                        $placeholder
                        $jsEvents
                        $title
                        $value
                        $rsaAttribute
                    >";
            } else {
                return $this->_value;
            }

        }
        ///// TEXT ////
        if ($this->_renderer == self::RENDER_HIDDEN) {
        
        	$value = ShaUtilsCapcha::getRandomCode(50);
        	$this->_isEditable = true;
			return "<input type='hidden' name='".$name."' id='".$id."' value='$value'>";
        
        }
        
        ///// PASSWORD ////
        elseif ($this->_renderer == self::RENDER_TYPE_PASSWORD) {

        	$value = (!is_array($this->_value) && $this->_value!="") ? ' value = "'.ShaUtilsString::quoteDblProtection($this->_value).'" ' : "";
        	
            if ($this->_isEditable) {
                return "
                    <input
                       ".$required."
                        type='password'
                        $cssStyle
                        name='".$name."'
                        id='".$id."'
                        class='$submitable cms_input ".$this->_inputCssClass."'
                        $placeholder
                        $jsEvents
                        $title
                        $value
                        $rsaAttribute
                    >";
            } else {
                return $this->_value;
            }

        }

        ///// TEXTAREA ////
        elseif ($this->_renderer == self::RENDER_TYPE_TEXTAREA){

        	$value = (!is_array($this->_value) && $this->_value!="") ? ShaUtilsString::cleanForBalise($this->_value) : "";
        	
            if ($this->_isEditable) {
                return "
                    <textarea
                        ".$required."
                        $cssStyle
                        name='".$name."'
                        id='".$id."'
                        class='cms_area $submitable ".$this->_inputCssClass."'
                        $placeholder
                        $jsEvents
                        $title
                        $rsaAttribute
                    >$value</textarea>";
            } else {
                return $this->_value;
            }

        }

        //// RADIOBOX ////
        elseif ($this->_renderer == self::RENDER_TYPE_RADIOBOX){

            $disabled = (!$this->_isEditable) ? " disabled " : "";

            if (!isset($this->_datas)){
                throw new Exception(
                    __CLASS__."::".__FUNCTION__." : The RADIOBOX type field must have datas defined (use setDatas)"
                );
            }

            if (!is_array($this->_datas)){
                throw new Exception(
                    __CLASS__."::".__FUNCTION__." : _datas attribute must be an array for RADIOBOX field type "
                );
            }

            $render = "";
            $index = 0;
            foreach ($this->_datas as $key => $value) {
                $checked = ($key == $this->_value) ? "checked" : "";
                $render .=
                    "<div class='cms_radio'>
                    	<input
	                        $required
	                        $disabled
	                        $cssStyle
	                        type='radio'
	                        name='".$name."'
	                        id='".$id."[".$index."]'
	                        $checked
	                        $jsEvents
	                        $title
	                        value='".$key."'
	                        class='$submitable ".$this->_inputCssClass."'
	                        >".$value."
	                  </div>"
                ;
                $index++;
            }
            return $render;

        }

        //// CHECKBOX ////
        elseif ($this->_renderer == self::RENDER_TYPE_CHECKBOX){

            $disabled = (!$this->_isEditable) ? " disabled " : "";

            if (!isset($this->_datas)){
                throw new Exception(
                    __CLASS__."::".__FUNCTION__." : The CHECKBOX type field must have datas defined (use setDatas)"
                );
            }

            if (!is_array($this->_datas)){
                throw new Exception(
                    __CLASS__."::".__FUNCTION__." : _datas must be an array for CHECKBOX field type"
                );
            }

            if (!is_array($this->_value)) {
            	$this->_value = array($this->_value);
            }

            $render = "";
            $index = 0;
            foreach ($this->_datas as $key => $value) {
                $checked = (in_array($key, $this->_value)) ? "checked" : "";
                $render .=
                    "<input
                        $required
                        $disabled
                        $cssStyle
                        type='checkbox'
                        name='".$name."'
                        id='".$id."[".$index."]'
                        $checked
                        $jsEvents
                        $title
                        value='".$key."'
                        class='$submitable ".$this->_inputCssClass."'
                        >".$value
                ;
                $index++;
            }
            return $render;

        }

        //// COMBOBOX ////
        elseif ($this->_renderer == self::RENDER_TYPE_COMBOBOX || $this->_renderer == self::RENDER_TYPE_DB_COMBOBOX){

            $disabled = (!$this->_isEditable) ? " disabled " : "";

            if (!isset($this->_datas)){
                throw new Exception(
                    __CLASS__."::".__FUNCTION__." : The COMBOBOX type field must have datas defined (use setDatas)"
                );
            }

            if (!is_array($this->_datas)){
                throw new Exception(
                    __CLASS__."::".__FUNCTION__." : _datas must be an array for COMBOBOX field type"
                );
            }
            
            if (!is_array($this->_value)) {
            	$this->_value = array($this->_value);
            }

            $options = "
                <select
                    ".( ($this->_isMultipleSelection) ? "multiple" : "" )."
                    $required
                    $disabled
                    $cssStyle
                    name='".$name."'
                    id='".$id."'
                    $jsEvents
                    $placeholder
                    $title
                    class='$submitable cms_input ".$this->_inputCssClass."'
                    >
                ";

            foreach ($this->_datas as $key => $value) {
                $selected = (in_array($key, $this->_value)) ? "selected" : "";
                $options .= "
                    <option
                        $selected
                        value='".$key."'
                    >
                    $value
                    </option>
               	";
            }
            $options .= "</select>";
            
            if ($this->_renderer == self::RENDER_TYPE_DB_COMBOBOX) {
            	

            	$this->_additionalJs[] = 
            	'
            		$j("#'.$id.'").pickList({
		        		sourceListLabel: "",
		        		targetListLabel: "",
		        		addAllClass: "btn btn-default",
		        		addClass: "btn btn-default",
		        		removeAllClass: "btn btn-default",
		        		removeClass: "btn btn-default"
		        	});
            	'            	           	
            	;

            }
            
            return $options;

        }

        //// CHECKBOX_LIST ////
        elseif ($this->_renderer == self::RENDER_TYPE_CHECKBOX_LIST){

            //CHECKBOXLIST
            //TODO : finish it
            /*return self::createMultipleSelectionList(
            	$idValue, 
            	"", 
            	$this->_datas, 
            	$this->_value, 
            	"", 
            	$cssStyle, 
            	"checkbox", 
            	""
            );*/
        	

        }

        //// RADIOBOX_LIST ////
        elseif ($this->_renderer == self::RENDER_TYPE_RADIOBOX_LIST){

            //RadioBoxList
            //TODO : finish it
            //return self::createMultipleSelectionList($idValue, $item['label'], $item['values'], $item['selectedValues'], "", $domWidth, "radiobox", (isset($item['onchange']))?$item['onchange']:"");

        }

        //// SUBMIT ////
        elseif ($this->_renderer == self::RENDER_TYPE_SUBMIT){

            return "
                <input
                    $cssStyle
                    onclick='Shaoline.submitForm(".$this->_parent->getGcId().",\"".$this->_parent->getDomId()."\")'
                    gcid='".$this->_parent->getGcId()."'
                    type='submit'
                    name='".$name."'
                    id='".$id."'
                    $jsEvents
                    $title
                    value='".$this->_value."'
                    class='$submitable cms_button ".$this->_inputCssClass."'
                />
            ";
            /*<span
                    name='".$name."'
                    id='".$name."'
                    $jsEvents
                    $title
                    value='".$this->_value."'
                    class='cms_button ".$this->_inputCssClass."'
                    onclick='Shaoline.submitForm(" . $this->_parent->getGcId(). ")'
                        </span>"*/
        }

        //// PICTURE ////
        elseif ($this->_renderer == self::RENDER_TYPE_PICTURE){

            $path = ($this->_value != "") ? $this->_value : "shaoline/resources/img/cms_no_picture.png";
			            
            if ($this->_isEditable){
            	
                return "
                	<input type='hidden' name='MAX_FILE_SIZE' value='".$this->getFileMaxSize()."'>
                	<input onchange=\"Shaoline.updateFileField('".$id."', '".$id."_picture')\" $required type='file' class='cmsFileInput' name='".$name."' id='".$id."'>
                    <input
                        $cssStyle
                        type='text'
                        id='".$id."_picture'
                        class='cmsFile ".$this->_inputCssClass."'
                        $placeholder
                        $title
                    >
					<div $title class='cmsFileBtn'></div>
                ";
            } else {
                return
                    "<img
                        $cssStyle
                        name='".$name."'
                        id='".$id."'
                        $jsEvents
                        class='".$this->_inputCssClass."'
                        alt='".$path."'
                        src='".$path."'
                    >";
            }

        }

        //// SWITCHVALUE ////
        elseif ($this->_renderer == self::RENDER_TYPE_SWITCHVALUE){

            $value = (!isset($this->_datas[$this->_value])) ? $this->_datas[$this->_value] : $this->_value;
            return $value;

        }

        //// SWITCHPICTURE ////
        elseif ($this->_renderer == self::RENDER_TYPE_SWITCHPICTURE){

            $value = (isset($this->_datas[$this->_value])) ? $this->_datas[$this->_value] : $this->_value;
            return "
                <img
                    $cssStyle
                    name='".$name."'
                    id='".$id."'
                    $jsEvents
                    $title
                    alt='".$value."'
                    src='".$value."'
                />";
        }

        //// CAPCHA ////
        elseif ($this->_renderer == self::RENDER_TYPE_CAPCHA){

        	$capchaDivDomId = "capcha_".$this->_parent->getGcId()."_".$this->getId();
        	$sCapcha = self::generateCapchaField($this, $this->_parent->getGcId(), $capchaDivDomId);
        	
            return "
            	<div id='".$capchaDivDomId."'>
               		$sCapcha
                </div>
				<br/>
				<input
				    $required
				    $cssStyle
				    type='text'
				    name='$name'
                    id='$id'
                    class='cms_capcha $submitable cms_input ".$this->_inputCssClass."'
                    $placeholder
                    $jsEvents
                    $title
                    >
				";

        } elseif ($this->_renderer == self::RENDER_TYPE_DATE) {

        	$value = (!is_array($this->_value) && $this->_value!="") ? " value = '".ShaUtilsString::quoteProtection($this->_value)."' " : "";
        	
        	if ($this->_datePickerParams == null){
        		$this->_datePickerParams = array(
        			'dateFormat' 	=> 'yy-mm-dd',
        			'changeYear' 	=> true,
        			'changeMonth' 	=> true
        		);
        	}
        	
        	
        	$datePickerParams = array();
        	foreach ($this->_datePickerParams as $key => $value) {
        		if ($value === (int)$value){
        			$datePickerParams[] = $key . ":" . $value;
        		} elseif ($value === true) {
        			$datePickerParams[] = $key . ":true";
        		} elseif ($value === false) {
        			$datePickerParams[] = $key . ":false";
        		} else {
        			$datePickerParams[] = $key . ":'" . $value ."'";
        		}
        	}
        	$js = "\$j('#".$id."').datepicker({  ".implode(",", $datePickerParams)."});";
        	
            ShaPage::addJsScriptForEndOfPage($js);
            ShaResponse::addJsScript($js);
        	if ($this->_isEditable) {
        		return "
        			<input
        			type='text'
        			$cssStyle
        			name='".$name."'
        			id='".$id."'
        			class='$submitable cms_input datepicker ".$this->_inputCssClass."'
        			$placeholder
        			$jsEvents
        			$title
        			$value
        			$rsaAttribute
        		>";

        	} else {
        		return $this->_value;
            }

        }

        return "";
    }

	public static function generateCapchaField(ShaFormField $field, $parentGcId, $capchaDivDomId){

		$sCapcha = ShaUtilsCapcha::getCapchaPicture(
			$field->_capcha["capchaValue"],
			$field->_capcha["capchaWidth"],
			$field->_capcha["capchaHeight"],
			$field->_capcha["capchaNoiseDensity"],
			$field->_capcha["capchaColorBackR"],
			$field->_capcha["capchaColorBackG"],
			$field->_capcha["capchaColorBackB"],
			$field->_capcha["capchaColorFontR"],
			$field->_capcha["capchaColorFontG"],
			$field->_capcha["capchaColorFontB"],
			$field->_capcha["capchaColorNoiseR"],
			$field->_capcha["capchaColorNoiseG"],
			$field->_capcha["capchaColorNoiseB"]
		);
		
		$operation = new ShaOperationAction();
		$operation
		->setDaoClass("ShaForm")
		->setDaoMethod("updateCapcha")
		->setParameters(
			array(
				'fieldId' 			=> $field->getId(),
				'gcId' 				=> $parentGcId,
				'capchaDivDomId' 	=> $capchaDivDomId
			)
		)
		->save()
		;
		 
		return ShaUtilsString::replace($sCapcha, "<img ", "<img ".$operation->getDomEvent()." ");
	}

    /**
     * Construct a multiselector field type
     *
     * @param int    $id               HTML field ID
     * @param string $titre            Title of field
     * @param array  $mappingKeyValues Mapping Id/Key/Value
     * @param string $selectedValues   Selected values
     * @param string $cssClass         Additional CSS class
     * @param int    $width            ShaBddField width
     * @param string $type             Use checkbox or radiobox
     * @param string $onchange         JS onchange action
     *
     * @return string
     */
    public static function createMultipleSelectionList($id,$titre,$mappingKeyValues,$selectedValues, $cssClass, $cssStyle,$type,$onchange, $width){
        $onchange = (isset($onchange) && $onchange!="")?"onchange='".ShaUtilsString::getASCII($onchange)."'":"";
        $html = "<div class='multipleSelection".$cssClass."' ".$cssStyle."  >";
        $html .= "<input type='hidden' id='is-collapse-".$id."' value='0'>";
        $html .= "<h2 onclick=\"collapseMultipleList('".$id."')\">".$titre."</h2><ul id='ul-".$id."' style='width:".$width."px;display:none'>";
        $cpt=0;

        foreach ($mappingKeyValues as $mappingKeyValue) {
            $checked = (in_array($mappingKeyValue[0], $selectedValues))?"checked":"";
            $html.= "<li>";
            if ($type=="checkbox") {
                $html .= "<input name='".$id."' value='".$mappingKeyValue[0]."' type='checkbox' ".$onchange." $checked >";
            } else if ($type=="radiobox") {
                $checked = ($cpt==0)? "checked=checked":"";
                $html .= "<input name='".$id."' value='".$mappingKeyValue[0]."' type='radio' ".$onchange." $checked >";
            }
            $html.= "<span>".$mappingKeyValue[1]."</span>
					</li>";
            $cpt++;
        }

        $html .= "</ul></div>";
        return $html;
    }




}