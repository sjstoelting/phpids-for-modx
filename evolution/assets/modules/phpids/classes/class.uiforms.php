<?php
/**
 * Contains class for jQuery UI tabs
 *
 * @author Stefanie Janine Stoelting<mail@stefanie-stoelting.de>
 * @link http://code.google.com/p/phpids-for-modx/ Project home page
 * @link http://www.stefanie-stoelting.de/
 * @package PHPIDS
 * @license LGPL
 * @since 2011/11/26
 * @version 0.7
 */
class UIForms
{
  /**
   * Constant string for form type dialog
   */
  const FORM_TYPE_DIALOGCONFIRM = '#dialog-confirm';

  /**
   * Contains the form type property
   * @var string
   */
  private $_formType;

  /**
   * Contains the allowed form type properties
   * @var array
   */
  private $_allowedFormTypes;

  /**
   * The CSS ID of the form
   * @var string
   */
  private $_id = 'dialog';

  /**
   * Contains the Resizeble property
   * @var boolean Default false
   */
  private $_resizeble = false;

  /**
   * Contains the height property
   * @var int Default is 100
   */
  private $_height = 100;

  /**
   * Contains the width property
   * @var int Default is 100
   */
  private $_width = 350;

  /**
   * Contains the modal property
   * @var boolean Default true
   */
  private $_modal = true;

  /**
   * Contains the buttons for the form dialog
   * @var array
   */
  private $_buttons = array();

  /**
   * Contains the font size property, default is 10
   * @var float
   */
  private $_fontSize = 0.6;

  /**
   * Contains the JSON address property
   * @var string
   */
  private $_JSONaddress = '';

  /**
   * Contains the JSON parameters properties
   * @var array
   */
  private $_JSONparameters = array();

  /**
   * Contains the content of the form
   * @var string
   */
  private $_formContent = '';

  /**
   * Contains the JavaScript function name for the form content
   * @var string
   */
  private $_functionName = '';


  /**
   * Constructor, sets the allowed form types and validates the current form
   * type.
   *
   * @param string $formType The current form type, default is FORM_TYPE_DIALOGCONFIRM
   * @throws If the given form type is not one of the allowed form types
   */
  public function __construct($formType=self::FORM_TYPE_DIALOGCONFIRM)
  {
    $this->_allowedFormTypes = array(self::FORM_TYPE_DIALOGCONFIRM);

    if (in_array($formType, $this->_allowedFormTypes)) {
      $this->_formType = $formType;
    } else {
      throw new Exception('$formType has is not one of the allows form types.');
    }
  } // __construct
  /**
   * Returns the HTML ID property
   *
   * @return string The HTML ID to identify the the tab
   */
  public function getId()
  {
    return $this->_id;
  } // getId

  /**
   * Sets the HTML ID property
   *
   * @param string $value The HTML ID to identify the the tab
   * @throws $value is empty
   */
  public function setId($value)
  {
    if (!empty($value)) {
      $this->_id = $value;
    } else {
      throw new Exception('$value is empty.');
    }
  } // setId

  /**
   * Returns the property Resizeble
   *
   * @return boolean
   */
  public function getResizeble()
  {
    return $this->_resizeble;
  } // getResizeble

  /**
   * Sets the property resizeble
   *
   * @param boolean $value
   * @throws If $value is not a boolean
   */
  public function setResizeble($value)
  {
    if (is_bool($value)) {
      $this->_resizeble = $value;
    } else {
      throw new Exception('Value must be boolean.');
    }
  } // setResizeble

  /**
   * Returns the property height
   *
   * @return int
   */
  public function getHeight()
  {
    return $this->_height;
  } // getHeight

  /**
   * Sets the property height
   *
   * @param int $value
   * @throws If $value is not an int
   */
  public function setHeight($value)
  {
    if (is_int($value)) {
      $this->_height = $value;
    } else {
      throw new Exception('Value for height is not an int.');
    }
  } // setHeight

  /**
   * Returns the property width
   *
   * @return int
   */
  public function getWidth()
  {
    return $this->_width;
  } // getWidth

  /**
   * Sets the property width
   *
   * @param int $value
   * @throws If $value is not an int
   */
  public function setWidth($value)
  {
    if (is_int($value)) {
      $this->_width = $value;
    } else {
      throw new Exception('Value for width is not an int.');
    }
  } // setWidth

  /**
   * Returns the property modal
   *
   * @return boolean
   */
  public function getModal()
  {
    return $this->_modal;
  } // getModal

  /**
   * Sets the property modal
   *
   * @param boolean $value
   * @throws If $value is not a boolean
   */
  public function setModal($value)
  {
    if (is_bool($value)) {
      $this->_modal = $value;
    } else {
      throw new Exception('Value is not boolean.');
    }
  } // setModal

  /**
   * Adds a new button
   *
   * @param string $name The name of the button
   * @param string $caption The caption of the button
   * @param string $function The JavaScript function of the button
   * @return boolean whether the button was created, or not
   */
  public function addButton($name, $caption, $function)
  {
    $this->_buttons[$name] = array('caption' => $caption, 'function' => $function);

    return array_key_exists($name, $this->_buttons);
  } // addButton

  /**
   * Removes a button
   *
   * @param string $name The name of the button, that should be removed
   * @return boolean whether the button was removed, or not, for example if the
   *         the name doesn't exist, the result is false
   */
  public function removeButton($name)
  {
    if (array_key_exists($name, $this->_buttons)) {
      unset($this->_buttons[$name]);

      return true;
    } else {
      return false;
    }
  } // removeButton

  /**
   * Returns all buttons
   *
   * @return array
   */
  public function getButtons()
  {
    return $this->_buttons;
  } // getButtons

  /**
   * Returns the font size property in em
   *
   * @return int Font size in em
   */
  public function getFontSize()
  {
    return $this->_fontSize;
  } // getFontSize

  /**
   * Sets the font size property in em
   *
   * @param int $value Font size in em
   * @throws $value is not a float
   */
  public function setFontSize($value)
  {
    if (is_float($value)) {
      $this->_fontSize = $value;
    } else {
      throw new Exception('$value is not a float.');
    }
  } // setFontSize

  /**
   * Sets the JSON  address property
   *
   * @return string
   */
  public function getJSONaddress()
  {
    return $this->_JSONaddress;
  } // getJSONaddress

  /**
   * Sets the JSON address
   *
   * @param string $value The address for the JSON command
   */
  public function setJSONaddress($value)
  {
    $this->_JSONaddress = $value;
  } // setJSONaddress

  /**
   * Returns all JSON parameters
   *
   * @return array All parameters in an array
   */
  public function getJSONparameters()
  {
    return $this->_JSONparameters;
  } // getJSONparameters

  /**
   * Returns the value for the given JSON parameter.
   * 
   * @param string $name The name of the parameter
   * @return string the value of the parameter
   */
  public function getJSONParameter($name)
  {
    if (array_key_exists($name, $this->_JSONparameters)) {
      return $this->_JSONparameters[$name];
    } else {
      throw new Exception('A JSON parameter with this name does not exist.');
    }
  } // getJSONParameter
  
  /**
   * Sets a JSON parameter
   *
   * @param string $name The name of the parameter
   * @param string $value The content of the parameter
   * @return boolean whether the JSON parameter was set, or not
   * @throws Empty $name
   */
  public function setJSONparameter($name, $value)
  {
    if (!empty ($name)) {
      $this->_JSONparameters[$name] = $value;

      return array_key_exists($name, $this->_JSONparameters);
    } else {
      throw new Exception('The property cannot have an empty name');
    }
  } // setJSONparameter

  /**
   * Returns the content of the form
   *
   * @return string The content of the form
   */
  public function getFormContent()
  {
    return $this->_formContent;
  } // getFormContent

  /**
   * Sets the form content property
   *
   * @param string $value The content of the form
   */
  public function setFormContent($value)
  {
    $this->_formContent = $value;
  } // setFormContent

  /**
   * Returns the name of the JavaScript function property
   *
   * @return string The name of the JavaScript function
   */
  public function getFunctionName()
  {
    return $this->_functionName;
  } // getFunctionName

  /**
   * Sets the name of the JavaScript function property. The function must be
   * closed by brackets, the brackets may contain function parameters.
   *
   * @param string The name of the JavaScript function
   */
  public function setFunctionName($value)
  {
    $this->_functionName = $value;
  } // setFunctionName

  /**
   * Returns the JavaScript for the form with getting the form content from a
   * JSON call.
   *
   * @return string The complete form JavaScript
   */
  public function getFormJSON()
  {
    // Set boolean strings
    $modal = ($this->_modal) ? 'true' : 'false';

    // Set function name
    if (!empty($this->_functionName)) {
      $functionName = "    function $this->_functionName {\n";
      $functionEnd = "    }\n";
    } else {
      $functionName = '';
      $functionEnd = '';
    }

    $result = $functionName . '      $.getJSON(\'' . $this->_JSONaddress . '\', {';

    foreach ($this->_JSONparameters as $key => $value) {
      $result .= "'$key':$value, ";
    }
    // Remove the last comma
    $result = substr($result, 0, -2);

    $result .= "}, function(data) {\n"
              ."        var result = "
              .$this->_formContent
              ."        \$('#$this->_id').html(result);\n"
              ."        \$(\"#$this->_id\").dialog({\n"
              ."          width: $this->_width,\n"
              ."          modal: $modal,\n"
              ."          buttons : {\n";

    foreach ($this->_buttons as $button) {
      $result .= "            \"" . $button['caption']
                ."\" : function() {\n" . $button['function']
                ."            },\n";
    }
    // Remove last two characters
    $result = substr($result, 0, -2) . "\n";

    $result .= "          }\n"
              ."        });\n"
              ."\n"
              ."        \$('#$this->_id').css('font-size', '" . $this->_fontSize . "em');\n"
              ."        $('#$this->_id').dialog('open');\n"
              ."      });\n"
              .$functionEnd;

    return $result;
  } // getFormJSON

  /**
   * Returns the JavaScript for the form.
   *
   * @return string The complete form JavaScript
   */
  public function getForm()
  {
    // Set boolean strings
    $modal = ($this->_modal) ? 'true' : 'false';

    // Set function name
    if (!empty($this->_functionName)) {
      $functionName = "    function $this->_functionName {\n";
      $functionEnd = "    }\n";
    } else {
      $functionName = '';
      $functionEnd = '';
    }

    $result = $functionName
             ."        var result = "
             .$this->_formContent
             ."        \$('#$this->_id').html(result);\n"
             ."        \$(\"#$this->_id\").dialog({\n"
             ."          width: $this->_width,\n"
             ."          modal: $modal,\n"
             ."          buttons : {\n";

    foreach ($this->_buttons as $button) {
      $result .= "            \"" . $button['caption']
                ."\" : function() {\n" . $button['function']
                ."            },\n";
    }
    // Remove last two characters
    $result = substr($result, 0, -2) . "\n";

    $result .= "          }\n"
              ."        });\n"
              ."\n"
              ."        \$('#$this->_id').css('font-size', '" . $this->_fontSize . "em');\n"
              ."        $('#$this->_id').dialog('open');\n"
              .$functionEnd;

    return $result;
  } // getForm
} // UIForms