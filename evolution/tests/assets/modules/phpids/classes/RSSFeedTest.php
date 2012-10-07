<?php
require_once 'PHPUnit/Framework.php';

require_once dirname(__FILE__).'/../../../../../assets/modules/phpids/classes/class.rssfeeds.php';

/**
 * Test class for RSSFeed.
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
class RSSFeedTest extends PHPUnit_Framework_TestCase
{
  /**
   * @var RSSFeed
   */
  protected $object;

  /**
   * Sets up the fixture, for example, opens a network connection.
   * This method is called before a test is executed.
   */
  protected function setUp()
  {
    $this->object = new RSSFeed();
  } // setUp

  /**
   * Tears down the fixture, for example, closes a network connection.
   * This method is called after a test is executed.
   */
  protected function tearDown()
  {
  } // tearDown

  /**
   * Check default maximum entries
   */
  public function testGetMaxEntries()
  {
    $this->assertEquals(100, $this->object->getMaxEntries());
  } // testGetMaxEntries

  /**
   * Test for 10 entries.
   */
  public function testSetMaxEntries()
  {
    $this->object->setMaxEntries(10);
    $this->assertEquals(10, $this->object->getMaxEntries());
  } // testSetMaxEntries

  /**
   * Get a RSS feed from PHPIDS and test the entries for more than 0.
   */
  public function testCreateEntryList()
  {
    $this->object->createEntryList('https://trac.php-ids.org/index.fcgi/log/trunk/lib/IDS/default_filter.xml?limit=10&format=rss');
    $this->assertEquals(0, count($this->object->count()));
  } // testCreateEntryList

  /**
   * Test for rewinding the entry list.
   */
  public function testRewind()
  {
    $this->object->rewind();
    $this->assertEquals(0, $this->object->key());
  } // testRewind

  /**
   * Tests, if the current entry is an RSSEntry.
   */
  public function testCurrent()
  {
    $this->assertEquals('RSSEntry', get_class($this->object->current()));
  } // testCurrent

  /**
   * Test for the current position.
   */
  public function testKey()
  {
    $this->assertEquals(0, $this->object->key());
  } // testKey

  /**
   * Test the forwarding in the list.
   */
  public function testNext()
  {
    $this->object->next();
    $this->assertEquals(1, $this->object->key());
  } // testNext

  /**
   * Test, if the current position is valid.
   */
  public function testValid()
  {
    $this->assertTrue($this->object->valid());
  } // testValid

  /**
   * Test for going backward.
   */
  public function testPrevious()
  {
    $this->object->previous();
    $this->assertEquals(0, $this->object->key());
  } // testPrevious

  /**
   * Test for going to the last item.
   */
  public function testLast()
  {
    $this->object->last();
    $this->assertTrue($this->object->valid());
  } // testLast

  /**
   * Test for count of items
   */
  public function testCount()
  {
    $this->assertTrue($this->object->count() > 0);
  } // testCount
} // RSSFeedTest