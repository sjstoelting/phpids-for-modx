<?php
/*
 * RSS feed
 * @name PHPIDS
 *
 * @author Stefanie Janine Stoelting<mail@stefanie-stoelting.de>
 * @link http://code.google.com/p/phpids-for-modx/ Project home page
 * @link http://www.stefanie-stoelting.de/
 * @package PHPIDS
 * @subpackage RSS
 * @license LGPL
 * @since 2011/11/26
 * @version 0.7
 */
class RSSEntry
{
  /**
   * Entry date
   * @var date
   */
  private $_date;

  /**
   *
   * @var <type>
   */
  private $_timeStamp;

  /**
   * The link published with the entry
   * @var string
   */
  private $_link;

  /**
   * The title of the entry
   * @var string
   */
  private $_title;

  /**
   * The text of the entry
   * @var text
   */
  private $_text;

  /**
   * The summary length, default length is 100 characters
   * @var int
   */
  private $_summaryLength = 100;
  /**
   * The summary of the entry
   * @var string
   */
  private $_summary;

  /**
   * Returns the date property
   *
   * @return string The date property
   */
  public function getDate()
  {
    return $this->_date;
  } // getDate

  /**
   * Sets the date property
   *
   * @param string The date property
   */
  public function setDate($value)
  {
    $this->_date = $value;
  } // setDate

  /**
   * Returns the UNIX timestamp property
   * 
   * @return timestamp The UNIX timestamp
   */
  public function getTimeStamp()
  {
    return $this->_timeStamp;
  } // getTimeStamp

  /**
   * Sets the timestamp property by converting $value into an UNIX timestamp
   *
   * @param string $value The timestamp as english formatted datetime
   */
  public function setTimeStamp($value)
  {
    $this->_timeStamp = strtotime($value);
  } // setTimeStamp

  /**
   * Returns the link property
   *
   * @return string The link property
   */
  public function getLink()
  {
    return $this->_link;
  } // getLink

  /**
   * Sets the link property
   *
   * @param string $value The link property
   */
  public function setLink($value)
  {
    $this->_link = $value;
  } // setLink

  /**
   * Returns the title property
   *
   * @return string The title property
   */
  public function getTitle()
  {
    return $this->_title;
  } // getTitle

  /**
   * Sets the the title property
   *
   * @param string $value The title property
   */
  public function setTitle($value)
  {
    $this->_title = $value;
  } // setTitle

  /**
   * Returns the text property
   *
   * @return string The text property
   */
  public function getText()
  {
    return $this->_text;
  } // getText

  /**
   * Sets the text property and calculates the summary text
   *
   * @param string $value The text property
   */
  public function setText($value)
  {
    $this->_text = $value;

    // Create summary as a shortened body and remove images, extraneous line breaks, etc.
    $summary = $value;
    $summary = eregi_replace('<img[^>]*>', '', $summary);
    $summary = eregi_replace('^(<br[ ]?/>)*', '', $summary);
    $summary = eregi_replace('(<br[ ]?/>)*$', '', $summary);

    // Truncate summary line to the defined character length
    if(strlen($summary) > $this->_summaryLength) {
      $summary = substr($summary, 0, $this->_summaryLength) . '...';
    }

    $this->_summary = $summary;

  } // setText

  /**
   * Returns the property summary length
   *
   * @return int The summary length
   */
  public function getSummaryLength()
  {
    return $this->_summaryLength;
  } // getSummaryLength

  /**
   * Sets the property summary length and changes the summary
   *
   * @param int $value The property summary length
   * @throws If $value is not an integer
   */
  public function setSummaryLength($value)
  {
    if (is_int($value)) {
      $this->_summaryLength = $value;
      $this->_summary = substr($this->_text, 0, $this->_summaryLength);
    } else {
      throw new Exception('The value for summary length is not an integer');
    }
  } // setSummaryLength

  /**
   * Returns the summary text
   *
   * @return string The summary text
   */
  public function getSummary()
  {
    return $this->_summary;
  } // getSummary
} // RSSEntry