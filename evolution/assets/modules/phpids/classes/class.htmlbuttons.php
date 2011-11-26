<?php
/**
 * Contains class for HTML
 *
 * @author Stefanie Janine Stoelting, mail@stefanie-stoelting.de
 * @link http://code.google.com/p/phpids-for-modx/ Project home page
 * @link http://www.stefanie-stoelting.de/
 * @package PHPIDS
 * @license LGPL
 * @since 2011/11/26
 * @version 0.7
 */
class HTMLButtons
{
  /**
   * Constant string for submit buttons.
   */
  const TYPE_SUBMIT = 'submit';

  /**
   * Constant string for reset buttons.
   */
  const TYPE_RESET = 'reset';

  /**
   * Constant string for image buttons.
   */
  const TYPE_IMAGE = 'image';

  /**
   * Constant string for buttons with JavaScript, meaning empty types.
   */
  const TYPE_BUTTON = '';

  /**
   * The style class for the button.
   * @var string
   */
  private $_styleClass = '';

  /**
   * The caption for the button.
   * @var string
   */
  private $_caption = '';

  /**
   * The name of the button.
   * @var string
   */
  private $_name = '';

  /**
   * The id of the button.
   * @var string
   */
  private $_id = '';

  /**
   * The button on click event
   * @var string
   */
  private $_onClick = '';

  /**
   * The type of the button.
   * @var string
   */
  private $_type = '';

  /**
   * The image source of the button
   * @var string
   */
  private $_source = '';

  /**
   * The alternate caption for image buttons.
   * @var string
   */
  private $_alt = '';


  /**
   * Sets the class of the button.
   *
   * @param string $value The class of the button
   */
  public function setStyleClass($value)
  {
    $this->_styleClass = $value;
  } // setStyleClass

  /**
   * Returns the class of the button.
   *
   * @return string The class of the button
   */
  public function getStyleClass()
  {
    return $this->_styleClass;
  } // getStyleClass

  /**
   * Sets the button caption.
   *
   * @param string $value The button caption.
   */
  public function setCaption($value)
  {
    $this->_caption = $value;
  } // setCaption

  /**
   * Returns the button caption.
   *
   * @return string The button caption.
   */
  public function getCaption()
  {
    return $this->_caption;
  } // getCaption

  /**
   * Sets the on click event.
   *
   * @param string $value The on click event
   */
  public function setOnClick($value)
  {
    $this->_onClick = $value;
  } // setOnClick

  /**
   * Returns the on click event.
   *
   * @return string The on click event
   */
  public function getOnClick()
  {
    return $this->_onClick;
  } // getOnClick

  /**
   * Sets the name of the button.
   *
   * @param string $value The name of the button
   */
  public function setName($value)
  {
    $this->_name = $value;
  } // setName

  /**
   * Returns the name of the button
   *
   * @return string The name of the button
   */
  public function getName()
  {
    return $this->_name;
  } // getName

  /**
   * Set the id of the button.
   *
   * @param string $value The id of the button
   */
  public function setId($value)
  {
    $this->_id = $value;
  } // setId

  /**
   * Returns the id of the button.
   *
   * @return string The id of the button
   */
  public function getId()
  {
    return $this->_id;
  } // getId

  /**
   * Sets the button type and if the type is TYPE_IMAGE the src and alt
   * properties. On other types, the the src and alt properties are set to
   * empty.
   *
   * @param string $buttonType The button type
   * @param string $src The image source
   * @param string $alt The alternate text
   * @throws If $buttonType is TYPE_IMAGE and $src is empty
   * @throws If $buttonType is none of the allowed types
   */
  public function setType($buttonType, $src='', $alt='')
  {
    $this->_source = '';
    $this->_alt = '';

    switch ($buttonType) {
      case self::TYPE_IMAGE:
        if (!empty($src)) {
          $this->_type = self::TYPE_IMAGE;
          $this->_source = $src;
          $this->_alt = $alt;
        } else {
          throw new Exception('An image source must be set.');
        }
        break;

      case self::TYPE_RESET:
        $this->_type = self::TYPE_RESET;
        break;

      case self::TYPE_SUBMIT:
        $this->_type = self::TYPE_SUBMIT;
        break;

      case self::TYPE_BUTTON:
        $this->_type = self::TYPE_BUTTON;
        break;

      default:
        throw new Exception('$buttonType is not one of the allowed types.');
        break;
    }
  } // setType

  /**
   * Returns the complete HTML button.
   *
   * @return string The complete HTML button
   */
  public function getButton()
  {
    $id = (!empty($this->_id)) ? ' id="' . $this->_id . '"': '';
    $name = (!empty($this->_name)) ? 'name="' . $this->_name . '"': '';
    $styleClass = (!empty($this->_styleClass)) ? 'class="' . $this->_styleClass . '"': '';
    $onClick = (!empty($this->_onClick)) ? 'onclick="' . $this->_onClick . '"': '';
    $caption = (!empty($this->_caption)) ? 'value="' . $this->_caption . '"': '';
    $buttonType = (!empty($this->_type)) ? 'value="' . $this->_type . '"': '';
    $src = (!empty($this->_source)) ? 'value="' . $this->_source . '"': '';
    $alt = (!empty($this->_alt)) ? 'value="' . $this->_alt . '"': '';

    $result = "  <input type=\"button\" $id $name $styleClass $onClick $buttonType $caption $src $alt />\n";

    return $result;
  } // getButton
} // HTMLButtons