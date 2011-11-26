<?php
/*
 * A simple RSS feed reader, does not verify the result of a call
 * @name PHPIDS
 *
 * @author Stefanie Janine Stoelting, mail@stefanie-stoelting.de
 * @link http://code.google.com/p/phpids-for-modx/ Project home page
 * @link http://www.stefanie-stoelting.de/
 * @package PHPIDS
 * @subpackage RSS
 * @license LGPL
 * @since 2011/11/26
 * @version 0.7
 */
class RSSFeed implements Iterator
{
  /**
   * Contains the RSS posts
   * @var array
   */
  private $_entries = array();

  /**
   * The maximum count of entries
   * @var int
   */
  private $_maxEntries = 100;

  /**
   * The counter for iteration
   * @var int
   */
  private $_position = 0;


  /**
   * Initializes default values
   */
  public function  __construct() {
    $this->_position = 0;
  } // __construct

  /**
   * Returns the maximum entry property.
   *
   * @return int The maximum entry property
   */
  public function getMaxEntries()
  {
    return $this->_maxEntries;
  } // getMaxEntries
  
  /**
   * Sets the maximum entry property and reduces the entries, if the maximum
   * entries are higher than the current entry count to the maximum.
   *
   * @param int $value The maximum entry property
   */
  public function setMaxEntries($value)
  {
    if (is_int($value)) {
      $this->_maxEntries = $value;

      if (count($this->_entries) > $this->_maxEntries) {
        // Reduce the entries to the maximum allowed entries
        $this->_entries = array_slice($this->_entries, 0, $this->_maxEntries);
      }
    } else {
      throw new Exception('The value for maximum entries is not an integer');
    }
  } // setMaxEntries

  /**
   * Creates all entries from the given uri.
   *
   * @param string $feed_uri The uri of the the RSS feed
   * @return boolean whether a connection to the feed uri could be established,
   *                 or not
   */
  function createEntryList($feed_uri)
  {
    $result = false;

    if (file_exists($feed_uri)) {
      $xml_source = file_get_contents($feed_uri);
      $xml = simplexml_load_string($xml_source);

      // If there are entries, we need to empty the array
      $this->_entries = array();

      $i = 0;
      if(count($xml) > 0) {
        foreach($xml->channel->item as $item){
          if ($i < $this->_maxEntries) {
            // Add entries to the array
            $entry = new RSSEntry();
            $entry->setDate((string) $item->pubDate);
            $entry->setTimeStamp($item->pubDate);
            $entry->setLink((string) $item->link);
            $entry->setTitle((string) $item->title);
            $entry->setText((string) $item->description);

            $this->_entries[] = $entry;

          } else {
            // If the maximum entries are reached, there is no need add more entries
            exit;
          }
          $i++;
        }
      }
      $result = true;
    }

    return $result;
  } // createEntryList

  /**
   * Rewinds the iterator.
   */
  public function rewind()
  {
    $this->_position = 0;
  } // rewind

  /**
   * Returns the current RSS entry.
   *
   * @return RSSEntry The current RSS entry
   */
  public function current()
  {
    return $this->_entries[$this->_position];
  } // current

  /**
   * Returns the current position.
   *
   * @return int The current position
   */
  public function key()
  {
    return $this->_position;
  } // key

  /**
   * Go to the next entry.
   */
  public function next()
  {
    ++$this->_position;
  } // next

  /**
   * Wether the current entry is valid, or not.
   *
   * @return boolean
   */
  public function valid()
  {
    return isset($this->_entries[$this->_position]);
  } // valid

  /**
   * Go to the previous entry.
   */
  public function previous()
  {
    --$this->_position;
  } // previous

  /**
   * Go to the last entry.
   */
  public function last()
  {
    $this->_position = count($this->_entries) - 1;
  } // last

  /**
   * Returns the count of current entries.
   *
   * @return int The count of current entries
   */
  public function count()
  {
    return count($this->_entries);
  } // count
} // RSSFeed