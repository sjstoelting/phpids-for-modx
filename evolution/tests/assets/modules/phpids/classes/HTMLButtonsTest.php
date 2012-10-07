<?php
require_once dirname(__FILE__).'/../../../../../assets/modules/phpids/classes/class.htmlbuttons.php';

/**
 * Test class for HTMLButtonsTest.
 *
 * @author Stefanie Janine Stoelting<mail@stefanie-stoelting.de>
 * @link http://code.google.com/p/phpids-for-modx/ Project home page
 * @link http://www.stefanie-stoelting.de/
 * @package PHPIDS
 * @subpackage Test
 * @license LGPL
 * @since 2011/02/02
 * @version 0.6.5.alpha2
 */
class HTMLButtonsTest extends PHPUnit_Framework_TestCase
{
  /**
   * Constant string for testing style class.
   */
  const STYLE = 'styletest';

  /**
   * Constant string for testing the button caption (value).
   */
  const CAPTION = 'caption test';

  /**
   * Constant string for testing button name.
   */
  const NAME = 'btnname';

  /**
   * Constant string for testing onClick.
   */
  const ON_CLICK = 'this.form.btnname.value=\'TEST\'';
  /**
   * @var HTMLButtons
   */
  protected $object;

  /**
   * Sets up the fixture, for example, opens a network connection.
   * This method is called before a test is executed.
   */
  protected function setUp()
  {
    $this->object = new HTMLButtons;
  }

  /**
   * Tears down the fixture, for example, closes a network connection.
   * This method is called after a test is executed.
   */
  protected function tearDown()
  {
  }

  /**
   * Sets the style class property.
   */
  public function testSetStyleClass()
  {
    $this->object->setStyleClass(self::STYLE);

    $this->assertEquals(self::STYLE, $this->object->getStyleClass());
  } // testSetStyleClass

  /**
   * Tests style class
   */
  public function testGetStyleClass()
  {
    $this->object->setStyleClass(self::STYLE);

    $this->assertEquals(self::STYLE, $this->object->getStyleClass());
  } // testGetStyleClass

  /**
   * Sets the button caption (value) property.
   */
  public function testSetCaption()
  {
    $this->object->setCaption(self::CAPTION);

    $this->assertEquals(self::CAPTION, $this->object->getCaption());
  } // testSetCaption

  /**
   * Tests the button caption (value).
   */
  public function testGetCaption()
  {
    $this->object->setCaption(self::CAPTION);

    $this->assertEquals(self::CAPTION, $this->object->getCaption());
  } // testGetCaption

  /**
   * Sets the onClick property.
   */
  public function testSetOnClick()
  {
    $this->object->setOnClick(self::ON_CLICK);

    $this->assertEquals(self::ON_CLICK, $this->object->getOnClick());
  } // testSetOnClick

  /**
   * Tests the button onClick.
   */
  public function testGetOnClick()
  {
    $this->object->setOnClick(self::ON_CLICK);

    $this->assertEquals(self::ON_CLICK, $this->object->getOnClick());
  } // testGetOnClick

  /**
   * Sets the button name property.
   */
  public function testSetName()
  {
    $this->object->setName(self::NAME);

    $this->assertEquals(self::NAME, $this->object->getName());
  } // testSetName

  /**
   * Tests the button name.
   */
  public function testGetName()
  {
    $this->object->setName(self::NAME);

    $this->assertEquals(self::NAME, $this->object->getName());
  } // testGetName

  /**
   * Sets the id property.
   */
  public function testSetId()
  {
    $this->object->setId(self::NAME);

    $this->assertEquals(self::NAME, $this->object->getId());
  } // testSetId

  /**
   * Tests the id.
   */
  public function testGetId()
  {
    $this->object->setId(self::NAME);

    $this->assertEquals(self::NAME, $this->object->getId());
  } // testGetId

  /**
   * .
   */
  public function testSetType()
  {
    $this->object->setType(HTMLButtons::TYPE_SUBMIT);
  } // testSetType

  /**
   * Tests the result of all button properties.
   */
  public function testGetButton()
  {
    $expected = "<input type=\"button\" />\n";

    $this->assertEquals($expected, $this->object->getButton());

    $expected = "<input type=\"button\" id=\"" . self::NAME . "\" />\n";
    $this->object->setId(self::NAME);

    $this->assertEquals($expected, $this->object->getButton());

    $expected = "<input type=\"button\" id=\"" . self::NAME . "\" name=\"" . self::NAME . "\" />\n";
    $this->object->setId(self::NAME);
    $this->object->setName(self::NAME);

    $this->assertEquals($expected, $this->object->getButton());

    $expected = "<input type=\"button\" id=\"" . self::NAME . "\" name=\"" . self::NAME . "\" onclick=\"" . self::ON_CLICK . "\" />\n";
    $this->object->setId(self::NAME);
    $this->object->setName(self::NAME);
    $this->object->setOnClick(self::ON_CLICK);

    $this->assertEquals($expected, $this->object->getButton());
  } // testGetButton
} // HTMLButtonsTest