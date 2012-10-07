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
class UITabs
{
  /**
   * Contains the name of the tab
   * @var string
   */
  private $_id = 'tabs';

  /**
   * Contains the outer div CSS style class
   * @var string
   */
  private $_divStyleClass = '';

  /**
   * Contains the tab CSS style class
   * @var string
   */
  private $_tabsStyleClass = '';

  /**
   * Contains the tab informations
   *
   * @var array
   */
  private $_tab = array();

  /**
   * Contains the font size style, default is 10pt
   *
   * @var string
   */
  private $_fontSize = '10pt';

  /**
   * Contains the font size style for the tabs, default is 11pt
   *
   * @var string
   */
  private $_tabFontSize = '11pt';


  /**
   * Initializes a tab with the count of tabs given in tabcount
   *
   * @param int $tabcount Default is 0
   * @throws If $tabcount is not an integer
   */
  public function __construct($tabcount=0)
  {
    if (is_int($tabcount)) {
      for ($i=0; $i < $tabcount; $i++ ) {
        $this->_tab[$i + 1] = array('header' => '', 'content' => '');
      }
    } else {
      throw new Exception('$tabcount is not an integer.');
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
   * Returns the outer div style class
   *
   * @return string Name of the CSS style class
   */
  public function getDivStyleClass()
  {
    return $this->_divStyleClass;
  } // getDivStyleClass

  /**
   * Sets the style class property for the outer div
   *
   * @param string $value Name of the CSS style class
   */
  public function setDivStyleClass($value)
  {
    $this->_divStyleClass = $value;
  } // setDivStyleClass

  /**
   * Returns the tab style class
   *
   * @return string Name of the CSS style class
   */
  public function getTabStyleClass()
  {
    return $this->_tabsStyleClass;
  } // getTabStyleClass

  /**
   * Sets the style class property for the tab
   *
   * @param string $value Name of the CSS style class
   */
  public function setTabStyleClass($value)
  {
    $this->_tabsStyleClass = $value;
  } // setTabStyleClass

  /**
   * Sets a tab with content and header for the given identifier
   *
   * @param int $idTab
   * @param string $header
   * @param string $content
   * @throws If $idTab is not an integer
   */
  public function setTab($idTab, $header, $content)
  {
    if (is_int($idTab)) {
      $this->_tab[$idTab] = array('header' => $header, 'content' => $content);
    } else {
      throw new Exception('$idTab is not an integer');
    }
  } // setTab

  /**
   * Removes the tab with the given identifier
   *
   * @param int $idTab
   * @throws If $idTab is not an integer
   */
  public function removeTab($idTab)
  {
    if (is_int($idTab)) {
      unset ($this->_tab[$idTab]);
    } else {
      throw new Exception('$idTab is not an integer');
    }
  } // removeTab

  /**
   * Returns the header of a specific tab
   *
   * @param int $idTab
   * @return string
   * @throws If $idTab is not an integer
   */
  public function getHeader($idTab)
  {
    if (is_int($idTab)) {
      return $this->_tab[$idTab]['header'];
    } else {
      throw new Exception('$idTab is not an integer');
    }
  } // getHeader

  /**
   * Returns the content of a specific tab
   *
   * @param int $idTab
   * @return string
   * @throws If $idTab is not an integer
   */
  public function getContent($idTab)
  {
    if (is_int($idTab)) {
      return $this->_tab[$idTab]['content'];
    } else {
      throw new Exception('$idTab is not an integer');
    }
  } // getContent

  /**
   * Sets the font size property
   *
   * @param string $fontSize For example 10pt, which is the default
   */
  public function setFontSize($fontSize)
  {
    $this->_fontSize = $fontSize;
  } // setFontSize

  /**
   * Sets the tab font size property
   *
   * @param string $fontSize For example 11pt, which is the default
   */
  public function setTabFontSize($fontSize)
  {
    $this->_tabFontSize = $fontSize;
  } // setTabFontSize

  /**
   * Returns the complete HTML tab
   *
   * @return string The tab HTML
   */
  public function getTab()
  {
    $i = 1;
    $header = '';
    $content = '';
    $style = (!empty($this->_fontSize)) ? " style=\"font-size: $this->_fontSize;\"" : '';
    $class = (!empty($this->_divStyleClass)) ? " class=\"$this->_divStyleClass\"" : '';
    $tabStyle = (!empty($this->_tabFontSize)) ? " style=\"font-size: $this->_tabFontSize;\"" : '';
    $tabClass = (!empty($this->_tabsStyleClass)) ? " class=\"$this->_tabsStyleClass\"" : '';

    // Create tab headers and tabs
    foreach ($this->_tab as $tab) {
      $header .= '      <li><a href="#' . $this->_id . "-$i\">" . $tab['header'] . "</a></li>\n";

      $content .= '    <div id="' . $this->_id . "-$i\">\n"
                . "      <div id=\"dynamic$i\" $tabClass $tabStyle>\n"
                . $tab['content'] . "\n"
                . "      </div>\n"
                . "    </div>\n";

      $i++;
    }

    // Create the complete tab
    $result = '  <div id="' . $this->_id . "\" $style $class>\n"
            . "    <ul>\n"
            . $header
            . "    </ul>\n"
            . "\n"
            . $content
            . "  </div>\n";

    return $result;
  } // getTab
} // UITabs