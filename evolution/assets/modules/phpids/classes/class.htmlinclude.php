<?php
/**
 * Contains class for include CSS or JavaScript files
 *
 * @author Stefanie Janine Stoelting, mail@stefanie-stoelting.de
 * @link http://code.google.com/p/phpids-for-modx/ Project home page
 * @link http://www.stefanie-stoelting.de/
 * @package PHPIDS
 * @license LGPL
 * @since 2011/01/30
 * @version 0.6.5.alpha2
 */
class HtmlInclude
{
  /**
   * String constant for CSS includes
   */
  const CSS = 'css';

  /**
   * String constant for JavaScript includes
   */
  const JAVASCRIPT = 'js';
  /**
   * Contains the base path property
   * @var string
   */
  private $_basePath = '';

  /**
   * Contains the allowed file types: css for CSS and js for JavaScript
   * @var array
   */
  private $_allowedTypes = null;
  

  /**
   * Constructor for the HTML includes
   *
   * @param string $basePath The base path, default ist empty string
   */
  public function  __construct($basePath='') {
    // Setting the base path
    $this->_basePath = $basePath;

    // Initialize the allowed types
    $this->_allowedTypes = array(self::CSS, self::JAVASCRIPT);
  } // __construct

  /**
   * Returns the base path property
   * 
   * @return string
   */
  public function getBasePath()
  {
    return $this->_basePath;
  } // getBasePath

  /**
   * Sets the value of the base path property
   *
   * @param string $value
   */
  public function setBasePath($value)
  {
    $this->_basePath = $value;
  } // setBasePath

  /**
   * Returns a HTML for including CSS or JavaScript files
   *
   * @param string $type allowed is css for CSS and js for JavaScript
   * @param string $fileName the name of the file, may include directories
   * @return string The correct HTML include
   */
  public function getInclude($type, $fileName)
  {
    if (in_array($type, $this->_allowedTypes)) {

      switch ($type) {
        case self::CSS:
          $result = '  <link rel="stylesheet" href="'
                   .$this->_basePath . $fileName
                   ."\" type=\"text/css\" media=\"screen\" />\n";

          break;
        case self::JAVASCRIPT:
          $result = '  <script type="text/javascript" src="'
                   .$this->_basePath . $fileName
                   ."\"></script>\n";

          break;
      }

      return $result;
    } else {
      throw new Exception('$type is not one of the allowed types');
    }
  } // getInclude
} // HtmlInclude