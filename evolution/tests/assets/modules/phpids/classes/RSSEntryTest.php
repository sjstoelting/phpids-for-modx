<?php
require_once dirname(__FILE__).'/../../../../../assets/modules/phpids/classes/class.rssentry.php';

/**
 * Test class for RSSEntry.
 *
 * @author Stefanie Janine Stoelting<mail@stefanie-stoelting.de>
 * @link http://code.google.com/p/phpids-for-modx/ Project home page
 * @link http://www.stefanie-stoelting.de/
 * @package PHPIDS
 * @subpackage Test
 * @license LGPL
 * @since 2011/01/29
 * @version 0.6.5.alpha2
 */
class RSSEntryTest extends PHPUnit_Framework_TestCase
{
  /**
   * Constant string link for tests
   */
  const LINK_URI = 'http://code.google.com/p/phpids-for-modx/';

  /**
   * Constant string for title tests
   */
  const TITLE = 'This is a PHP Unit Test';

  /**
   * Constant string for text tests
   */
  const TEXT = 'Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et';

  /**
   * @var RSSEntry
   */
  protected $object;

  /**
   * Sets up the fixture, for example, opens a network connection.
   * This method is called before a test is executed.
   */
  protected function setUp()
  {
    $this->object = new RSSEntry;
  } // setUp

  /**
   * Tears down the fixture, for example, closes a network connection.
   * This method is called after a test is executed.
   */
  protected function tearDown()
  {
  } // tearDown

  /**
   * Insert current datetime.
   */
  public function testSetDate()
  {
    $this->object->setDate(getdate());
    $this->assertTrue(true);
  } // testSetDate

  /**
   * Checks, if the result is a valid datetim.
   */
  public function testGetDate()
  {
    $expected = date(DATE_RSS);
    $this->object->setDate($expected);

    $this->assertEquals($expected, $this->object->getDate());
  } // testGetDate

  /**
   * Sets the timestamp.
   */
  public function testSetTimeStamp()
  {
    $expected = date(DATE_RSS);

    $this->object->setTimeStamp($expected);
    
    $this->assertEquals(strtotime($expected), $this->object->getTimeStamp());
  } // testSetTimeStamp

  /**
   * Tests, if the timestamp is an integer.
   */
  public function testGetTimeStamp() 
  {
    $expected = date(DATE_RSS);

    $this->object->setTimeStamp($expected);
    
    $this->assertEquals(strtotime($expected), $this->object->getTimeStamp());
  } // testGetTimeStamp

  /**
   * Sets a link.
   */
  public function testSetLink()
  {
    $this->object->setLink(self::LINK_URI);

    $this->assertEquals(self::LINK_URI, $this->object->getLink());
  } // testSetLink

  /**
   * Verifies, that the link set is equal to the expected link.
   */
  public function testGetLink()
  {
    $this->object->setLink(self::LINK_URI);

    $this->assertEquals(self::LINK_URI, $this->object->getLink());
  } // testGetLink

  /**
   * Sets the title.
   */
  public function testSetTitle()
  {
    $this->object->setTitle(self::TITLE);

    $this->assertEquals(self::TITLE, $this->object->getTitle());
  } // testSetTitle

  /**
   * Verifies, that the title set is equal to the expected title.
   */
  public function testGetTitle()
  {
    $this->object->setTitle(self::TITLE);

    $this->assertEquals(self::TITLE, $this->object->getTitle());
  } // testGetTitle

  /**
   * Sets the text.
   */
  public function testSetText()
  {
    $this->object->setText(self::TEXT);

    $this->assertEquals(self::TEXT, $this->object->getText());
  } // testSetText

  /**
   * Verifies, that the text set is equal to the expected text.
   */
  public function testGetText()
  {
    $this->object->setText(self::TEXT);

    $this->assertEquals(self::TEXT, $this->object->getText());
  } // testGetText

  /**
   * Chechs, whether the summary lenght is the
   */
  public function testGetSummaryLength()
  {
    $this->assertEquals(100, $this->object->getSummaryLength());
  } // testGetSummaryLength

  /**
   * Verifies, that the summary length set is equal to the expected summary
   * length.
   */
  public function testSetSummaryLength()
  {
    $this->object->setSummaryLength(50);
    $this->assertEquals(50, $this->object->getSummaryLength());
  } // testSetSummaryLength

  /**
   * Verifies, that the summary set is equal to the expected summary.
   */
  public function testGetSummary()
  {
    $this->assertEquals(substr(self::TEXT, 0, 50), $this->object->getSummary());
  } // testGetSummary
} // RSSEntryTest