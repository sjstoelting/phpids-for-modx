<?php
require_once dirname(__FILE__).'/../../../../../assets/modules/phpids/classes/class.uitabs.php';

/**
 * Test class for HTMLTest.
 *
 * @author Stefanie Janine Stoelting<mail@stefanie-stoelting.de>
 * @link http://code.google.com/p/phpids-for-modx/ Project home page
 * @link http://www.stefanie-stoelting.de/
 * @package PHPIDS
 * @subpackage Test
 * @license LGPL
 * @since 2011/02/01
 * @version 0.6.5.alpha2
 */
class UITabsTest extends PHPUnit_Framework_TestCase
{
  /**
   * Constant string for checking ID.
   */
  const ID = 'TabID';

  /**
   * Constant string for checking the html class.
   */
  const TAB_CLASS = 'tabclass';

  /**
   * Constant integer for checking tab ID.
   */
  const ID_TAB = 1;

  /**
   * Constant string for checking the tab header text.
   */
  const HEADER_TEXT = 'Header Text';

  /**
   * Constant string for text tests
   */
  const CONTENT = 'Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et';

  /**
   * @var UITabs
   */
  protected $object;

  /**
   * Sets up the fixture, for example, opens a network connection.
   * This method is called before a test is executed.
   */
  protected function setUp()
  {
    $this->object = new UITabs();
  } // setUp

  /**
   * Tears down the fixture, for example, closes a network connection.
   * This method is called after a test is executed.
   */
  protected function tearDown()
  {
  } // tearDown

  /**
   * Sets the ID property.
   */
  public function testSetId()
  {
    $this->object->setId(self::ID);

    $this->assertEquals(self::ID, $this->object->getId());
  } // testSetId

  /**
   * Verifies, that the set ID matches the expected ID.
   */
  public function testGetId() 
  {
    $this->object->setId(self::ID);

    $this->assertEquals(self::ID, $this->object->getId());
  } // testGetId

  /**
   * Sets the div class property.
   */
  public function testSetDivStyleClass()
  {
    $this->object->setDivStyleClass(self::TAB_CLASS);
    $this->assertTrue(true);
  } // testSetDivStyleClass

  /**
   * Verifies, that the set style class matches the expected style class.
   */
  public function testGetDivStyleClass()
  {
    $this->assertEquals(self::TAB_CLASS, $this->object->getDivStyleClass());
  } // testGetDivStyleClass

  /**
   * Sets the div class property.
   */
  public function testSetTabStyleClass()
  {
    $this->object->setTabStyleClass(self::TAB_CLASS);
    $this->assertTrue(true);
  } // testSetTabStyleClass

  /**
   * Verifies, that the set style class matches the expected style class.
   */
  public function testGetTabStyleClass()
  {
    $this->assertEquals(self::TAB_CLASS, $this->object->getTabStyleClass());
  } // testGetTabStyleClass

  /**
   * Adds a new tab.
   */
  public function testSetTab() 
  {
    $this->object->setTab(self::ID_TAB, self::HEADER_TEXT, self::CONTENT);
    $this->assertTrue(true);
  } // testSetTab

  /**
   * Removes a tab.
   */
  public function testRemoveTab() 
  {
    $this->object->removeTab(self::ID_TAB);
    $this->assertTrue(true);
  } // testRemoveTab

  /**
   * Verifies, that the set tab header text matches the expected tab header
   * text.
   */
  public function testGetHeader() 
  {
    $this->object->setTab(self::ID_TAB, self::HEADER_TEXT, self::CONTENT);
    $this->assertEquals(self::HEADER_TEXT,
            $this->object->getHeader(self::ID_TAB));
  } // testGetHeader

  /**
   * Verifies, that the set tab content matches the expected tab contant.
   */
  public function testGetContent() 
  {
    $this->assertEquals(self::CONTENT, $this->object->getContent(self::ID_TAB));
  } // testGetContent

  /**
   * Sets the font size property.
   */
  public function testSetFontSize()
  {
    $this->object->setFontSize('12pt');

    $this->assertTrue(true);
  } // testSetFontSize

  /**
   * Verifies, that the expected result matches the current result.
   */
  public function testGetTab()
  {
    $expected = '  <div id="';
    $tab = $this->object->getTab();

    $this->assertEquals($expected, substr($tab, 0, strlen($expected)));
  } // testGetTab
} // UITabsTest