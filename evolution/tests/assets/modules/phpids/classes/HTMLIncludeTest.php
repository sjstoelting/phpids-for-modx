<?php
require_once dirname(__FILE__).'/../../../../../assets/modules/phpids/classes/class.htmlinclude.php';

/**
 * Test class for HtmlIncludeTest.
 *
 * @author Stefanie Janine Stoelting<mail@stefanie-stoelting.de>
 * @link http://code.google.com/p/phpids-for-modx/ Project home page
 * @link http://www.stefanie-stoelting.de/
 * @package PHPIDS
 * @subpackage Test
 * @license LGPL
 * @since 2011/01/30
 * @version 0.6.5.alpha2
 */
class HTMLIncludeTest extends PHPUnit_Framework_TestCase
{
  /**
   * @var HtmlInclude
   */
  protected $object;

  /**
   * Sets up the fixture, for example, opens a network connection.
   * This method is called before a test is executed.
   */
  protected function setUp()
  {
    $this->object = new HTMLInclude;
  } // setUp

  /**
   * Tears down the fixture, for example, closes a network connection.
   * This method is called after a test is executed.
   */
  protected function tearDown()
  {
  } // tearDown

  /**
   * Sets the base path with the directory of the test file.
   */
  public function testSetBasePath()
  {
    $this->object->setBasePath(dirname(__FILE__));

    $this->assertEquals(dirname(__FILE__), $this->object->getBasePath());
  } // testSetBasePath

  /**
   * Verifies, that the directory set is the expected directory.
   */
  public function testGetBasePath()
  {
    $this->object->setBasePath(dirname(__FILE__));

    $this->assertEquals(dirname(__FILE__), $this->object->getBasePath());
  } // testGetBasePath

  /**
   * Verifies, that the include returns the expected HTML include tags
   */
  public function testGetInclude() {
    $this->object->setBasePath(dirname(__FILE__));

    $fileName = 'test.js';
    $expected = '  <script type="text/javascript" src="'
               .dirname(__FILE__) . $fileName
               ."\"></script>\n";

    $this->assertEquals($expected, $this->object->getInclude(HtmlInclude::JAVASCRIPT, $fileName));

    $fileName = 'test.css';
    $expected = '  <link rel="stylesheet" href="'
               .dirname(__FILE__) . $fileName
               ."\" type=\"text/css\" media=\"screen\" />\n";

    $this->assertEquals($expected, $this->object->getInclude(HtmlInclude::CSS, $fileName));

    $expected = '  <link rel="stylesheet" href="'
               .dirname(__FILE__) . $fileName
               ."\" type=\"text/css\" media=\"screen, print\" />\n";

    $this->assertEquals($expected, $this->object->getInclude(HtmlInclude::CSS, $fileName, 'screen, print'));
  } // testGetInclude
} // HTMLIncludeTest