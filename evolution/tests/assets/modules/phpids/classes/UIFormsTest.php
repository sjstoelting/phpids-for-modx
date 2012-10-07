<?php
require_once dirname(__FILE__).'/../../../../../assets/modules/phpids/classes/class.uiforms.php';

/**
 * Test class for UIFormsTest.
 *
 * @author Stefanie Janine Stoelting<mail@stefanie-stoelting.de>
 * @link http://code.google.com/p/phpids-for-modx/ Project home page
 * @link http://www.stefanie-stoelting.de/
 * @package PHPIDS
 * @subpackage Test
 * @license LGPL
 * @since 2011/01/31
 * @version 0.6.5.alpha2
 */
class UIFormsTest extends PHPUnit_Framework_TestCase
{
  /**
   * Constant string for checking ID.
   */
  const ID = 'FormID';

  /**
   * Constant string for checking the JSON address.
   */
  const JSON_ADDRESS = 'manager/index.php';

  /**
   * Constant string button ID.
   */
  const BUTTON = 'button_close';

  /**
   * Constant string the name of a JSON parameter
   */
  const JSON_PARAMETER_NAME = 'a';

  /**
   * Constant string the value of a JSON parameter
   */
  const JSON_PARAMETER_VALUE = '112';

  /**
   * Constant string for text tests
   */
  const CONTENT = 'Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et';

  /**
   * Constant string for the function name
   */
  const FUNCTION_NAME = 'TestFunction';

  /**
   * @var UIForms
   */
  protected $object;

  /**
   * Sets up the fixture, for example, opens a network connection.
   * This method is called before a test is executed.
   */
  protected function setUp()
  {
    $this->object = new UIForms;
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
   * Sets the resizable .
   */
  public function testGetResizeble() 
  {
    $this->assertFalse($this->object->getResizeble());
  } // testGetResizeble

  /**
   * Verifies, that the set resizeble is the expected resizeble.
   */
  public function testSetResizeble() 
  {
    $this->object->setResizeble(true);
    $this->assertTrue($this->object->getResizeble());
  } // testSetResizeble

  /**
   * Verifies, that the current height is the expected default height.
   */
  public function testGetHeight() 
  {
    $expected = 100;

    $this->assertEquals($expected, $this->object->getHeight());
  } // testGetHeight

  /**
   * Verifies, that the set height matches the expected height.
   */
  public function testSetHeight() 
  {
    $expected = 120;

    $this->object->setHeight($expected);
    $this->assertEquals($expected, $this->object->getHeight());
  } // testSetHeight

  /**
   * Verifies, that the current width is the expected default width.
   */
  public function testGetWidth() 
  {
    $expected = 350;

    $this->assertEquals($expected, $this->object->getWidth());
  } // testGetWidth

  /**
   * Verifies, that the set width matches the expected width.
   */
  public function testSetWidth() 
  {
    $expected = 250;

    $this->object->setWidth($expected);
    $this->assertEquals($expected, $this->object->getWidth());
  } // testSetWidth

  /**
   * Verifies, that the current modal property is the expected default value.
   */
  public function testGetModal() 
  {
    $this->assertTrue($this->object->getModal());
  } // testGetModal

  /**
   * Verifies, that the set modal property matches the expected value.
   */
  public function testSetModal() 
  {
    $this->object->setModal(false);
    $this->assertFalse($this->object->getModal());
  } // testSetModal

  /**
   * Verifies, that a button could be added to the form.
   */
  public function testAddButton() 
  {
    $result = $this->object->addButton(self::BUTTON,
            'Close',
            "$(this).dialog(\"close\");\n");

    $this->assertTrue($result);

  } // testAddButton

  /**
   * Removes a button from the form.
   */
  public function testRemoveButton() 
  {
    $result = $this->object->addButton(self::BUTTON,
            'Close',
            "$(this).dialog(\"close\");\n");

    $this->assertTrue($result);
    $this->assertTrue($this->object->removeButton(self::BUTTON));
  } // testRemoveButton

  /**
   * Verifies, that the result of the method returns an array with one item.
   */
  public function testGetButtons() 
  {
    $result = $this->object->addButton(self::BUTTON,
            'Close',
            "$(this).dialog(\"close\");\n");

    $this->assertEquals(1, count($this->object->getButtons()));
  } // testGetButtons

  /**
   * Verifies, that the result of the font size is the default font size.
   */
  public function testGetFontSize() 
  {
    $expected = 0.6;

    $this->assertEquals($expected, $this->object->getFontSize());
  } // testGetFontSize

  /**
   * Verifies, that the set font size matches the expected font size.
   */
  public function testSetFontSize() 
  {
    $expected = 0.8;

    $this->object->setFontSize($expected);

    $this->assertEquals($expected, $this->object->getFontSize());
  } // testSetFontSize

  /**
   * Sets the JSON address property.
   */
  public function testSetJSONaddress()
  {
    $this->object->setJSONaddress(self::JSON_ADDRESS);

    $this->assertEquals(self::JSON_ADDRESS, $this->object->getJSONaddress());
  } // testSetJSONaddress

  /**
   * Verifies, that the current JSON address matches the expected JSON address.
   */
  public function testGetJSONaddress() 
  {
    $this->object->setJSONaddress(self::JSON_ADDRESS);

    $this->assertEquals(self::JSON_ADDRESS, $this->object->getJSONaddress());
  } // testGetJSONaddress

  /**
   * Verifies, that a JSON parameter could be added.
   */
  public function testSetJSONparameter()
  {
    $this->assertTrue(
            $this->object->setJSONparameter(
                    self::JSON_PARAMETER_NAME,
                    self::JSON_PARAMETER_VALUE));

  } // testSetJSONparameter

  /**
   * Verifies, that a JSON parameter exist in an array of JSON parameters.
   */
  public function testGetJSONparameters()
  {
    $this->assertTrue(
            $this->object->setJSONparameter(
                    self::JSON_PARAMETER_NAME,
                    self::JSON_PARAMETER_VALUE));

    $this->assertTrue(
            in_array(
              self::JSON_PARAMETER_VALUE,
              $this->object->getJSONparameters()
            )
    );
  } // testGetJSONparameters

  /**
   * Verifes, that a JSON parameter contains the expected value.
   */
  public function testGetJSONparameter() 
  {
    $this->assertEquals(
            self::JSON_PARAMETER_VALUE,
            $this->object->getJSONparameter(self::JSON_PARAMETER_NAME));
  } // testGetJSONparameter

  /**
   * Sets the form content.
   */
  public function testSetFormContent()
  {
    $this->object->setFormContent(self::CONTENT);

    $this->assertEquals(self::CONTENT, $this->object->getFormContent());
  } // testSetFormContent

  /**
   * Verifies, that the set form content matches the expected form content.
   */
  public function testGetFormContent() 
  {
    $this->object->setFormContent(self::CONTENT);

    $this->assertEquals(self::CONTENT, $this->object->getFormContent());
  } // testGetFormContent

  /**
   * Sets the function name.
   */
  public function testSetFunctionName()
  {
    $this->object->setFunctionName(self::FUNCTION_NAME);

    $this->assertEquals(self::FUNCTION_NAME, $this->object->getFunctionName());
  } // testSetFunctionName

  /**
   * Verifies, that the set function name matches the expecte function name.
   */
  public function testGetFunctionName() 
  {
    $this->assertEquals(self::FUNCTION_NAME, $this->object->getFunctionName());
  } // testGetFunctionName

  /**
   * Verifies the content is in the the form result
   */
  public function testGetFormJSON() 
  {
    $expected = strpos($this->object->getFormJSON(), self::CONTENT);

    $this->assertTrue($expected > 0);
  } // testGetFormJSON

  /**
   * Verifies the content is in the the form result
   */
  public function testGetForm()
  {
    $expected = strpos($this->object->getForm(), self::CONTENT);

    $this->assertTrue($expected > 0);
  }
} // UIFormsTest