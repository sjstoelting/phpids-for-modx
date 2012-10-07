<?php
require_once dirname(__FILE__).'/../../../../../assets/modules/phpids/classes/class.html.php';

/**
 * Test class for HTMLTest.
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
class HTMLTest extends PHPUnit_Framework_TestCase
{
  /**
   * Constant defining the XHTML transitional doctype
   */
  const DOC_TYPE_XHMTL_TRANSITIONAL = "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">\n<html xmlns=\"http://www.w3.org/1999/xhtml\">\n";

  /**
   * Constant defining the XHTML strict doctype
   */
  const DOC_TYPE_XHMTL_STRICT = "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Strict//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd\">\n<html xmlns=\"http://www.w3.org/1999/xhtml\">\n";
  /**
   * Constant defining the XHTML Frameset doctype
   */

  const DOC_TYPE_XHMTL_FRAMESET = "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Frameset//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-frameset.dtd\">\n<html xmlns=\"http://www.w3.org/1999/xhtml\">\n";

  /**
   * Constant defining text field
   */
  const TYPE_TEXT = 'text';

  /**
   * Constant defining textareas
   */
  const TYPE_TEXT_AREA = 'textarea';

  /**
   * Constant defining the content type text with charset UTF-8
   */
  const META_CONTENTTYPE = "  <meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\" />\n";

  /**
   * Constant string for text tests
   */
  const TEXT = 'Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et';

  /**
   * @var HTML
   */
  protected $object;


  /**
   * Sets up the fixture, for example, opens a network connection.
   * This method is called before a test is executed.
   */
  protected function setUp()
  {
    $this->object = new HTML;
  } // setUp

  /**
   * Tears down the fixture, for example, closes a network connection.
   * This method is called after a test is executed.
   */
  protected function tearDown()
  {
  } // tearDown

  /**
   * Verifies, that expected doc type is correct.
   */
  public function testGetDocTypeXHTMLTransitional()
  {
    $this->assertEquals(self::DOC_TYPE_XHMTL_TRANSITIONAL,
            $this->object->getDocTypeXHTMLTransitional());
  } // testGetDocTypeXHTMLTransitional

  /**
   * Verifies, that expected doc type is correct.
   */
  public function testGetDocTypeXHTMLStrict()
  {
    $this->assertEquals(self::DOC_TYPE_XHMTL_STRICT,
            $this->object->getDocTypeXHTMLStrict());
  } // testGetDocTypeXHTMLStrict

  /**
   * Verifies, that expected doc type is correct.
   */
  public function testGetDocTypeXHTMLFrameset()
  {
    $this->assertEquals(self::DOC_TYPE_XHMTL_FRAMESET,
            $this->object->getDocTypeXHTMLFrameset());
  } // testGetDocTypeXHTMLFrameset

  /**
   * Verifies, the the expected title tag is correct.
   */
  public function testGetTitle()
  {
    $title = 'TEST TITLE';
    $expected = "  <title>$title</title>\n";

    $this->assertEquals($this->object->getTitle($title), $expected);
  } // testGetTitle

  /**
   * Verifies, that the expected meta content type is correct.
   */
  public function testGetMetaContentType()
  {
    $this->assertEquals(self::META_CONTENTTYPE,
            $this->object->getMetaContentType());
  } // testGetMetaContentType

  /**
   * Verifies, that the expected type text type is correct.
   */
  public function testGetTYPE_TEXT()
  {
    $this->assertEquals(self::TYPE_TEXT, $this->object->getTYPE_TEXT());
  } // testGetTYPE_TEXT

  /**
   * Verifies, that the expected type text area is correct.
   */
  public function testGetTYPE_TEXT_AREA()
  {
    $this->assertEquals(self::TYPE_TEXT_AREA,
            $this->object->getTYPE_TEXT_AREA());
  } // testGetTYPE_TEXT_AREA

  /**
   * Verifies, that the expected form field is correct.
   */
  public function testGetFormField()
  {
    $sCaption = 'Caption';
    $sDataName = 'DataName';
    $expected = "                        '<p>" . $sCaption ."<br />' + \n"
               ."                          '<input name=\"$sDataName\" "
               ."type=\"text\" "
               ."readonly=\"readonly\" "
               ."size= \"40\" "
               ."value=\"' + data.$sDataName + '\" />' + \n"
               ."                        '</p>' + \n";

    $this->assertEquals($expected,
            $this->object->getFormField(
            $this->object->getTYPE_TEXT(), $sCaption, $sDataName, true));
  } // testGetFormField


  /**
   * Verifies, that the result is a correct div element
   */
  public function testGetDiv()
  {
    $id = 'testID';
    $class = 'testClass';

    $expected = "<div id=\"$id\" class=\"$class\">" . self::TEXT . "</div>\n";

    $this->assertEquals($expected, $this->object->getDIV(self::TEXT, $class, $id));

    $expected = "<div id=\"$id\">" . self::TEXT . "</div>\n";

    $this->assertEquals($expected, $this->object->getDIV(self::TEXT, '', $id));

    $expected = "<div class=\"$class\">" . self::TEXT . "</div>\n";

    $this->assertEquals($expected, $this->object->getDIV(self::TEXT, $class));

    $expected = "<div>" . self::TEXT . "</div>\n";

    $this->assertEquals($expected, $this->object->getDIV(self::TEXT));
  } // testGetDiv

  /**
   * Verifies, that the result is a correct p element
   */
  public function testGetP()
  {
    $id = 'testID';
    $class = 'testClass';

    $expected = "<p id=\"$id\" class=\"$class\">" . self::TEXT . "</p>\n";

    $this->assertEquals($expected, $this->object->getP(self::TEXT, $class, $id));

    $expected = "<p id=\"$id\">" . self::TEXT . "</p>\n";

    $this->assertEquals($expected, $this->object->getP(self::TEXT, '', $id));

    $expected = "<p class=\"$class\">" . self::TEXT . "</p>\n";

    $this->assertEquals($expected, $this->object->getP(self::TEXT, $class));

    $expected = "<p>" . self::TEXT . "</p>\n";

    $this->assertEquals($expected, $this->object->getP(self::TEXT));
  } // testGetP

  /**
   * Verifies, that the result is a correct span element
   */
  public function testGetSpan()
  {
    $id = 'testID';
    $class = 'testClass';

    $expected = "<span id=\"$id\" class=\"$class\">" . self::TEXT . "</span>";

    $this->assertEquals($expected, $this->object->getSpan(self::TEXT, $class, $id));

    $expected = "<span id=\"$id\">" . self::TEXT . "</span>";

    $this->assertEquals($expected, $this->object->getSpan(self::TEXT, '', $id));

    $expected = "<span class=\"$class\">" . self::TEXT . "</span>";

    $this->assertEquals($expected, $this->object->getSpan(self::TEXT, $class));

    $expected = "<span>" . self::TEXT . "</span>";

    $this->assertEquals($expected, $this->object->getSpan(self::TEXT));
  } // testGetSpan

  /**
   * Verifes, that the result is realy a line break
   */
  public function testGetBR()
  {
    $this->assertEquals("<br />\n", $this->object->getBR());
  } // testGetBR
} // HTMLTest