<?php
/*--------------------------------------------------------------------------+
This file is part of eStudy.
- Modulgruppe:  PHP-IDS
- Beschreibung: Klasse für das automatische aktualisieren der PHP-IDS Regeln
- Version:      0.2, 20-06-2011
- Autor(en):    Matthias Hecker <matthias.hecker@mni.fh-giessen.de>
    modified: Sami Mußbach <mussbach@uni.lueneburg.de>
    modified: Stefanie Janine Stoelting <mail@stefanie-stoelting.de>
+---------------------------------------------------------------------------+
This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or any later version.
+---------------------------------------------------------------------------+
This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.
You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
+--------------------------------------------------------------------------*/
 
/**
 * @name PHPIDS
 *
 * @author Stefanie Janine Stoelting<mail@stefanie-stoelting.de>
 * @link http://code.google.com/p/phpids-for-modx/ Project home page
 * @link http://www.stefanie-stoelting.de/phpids-news.html
 * @link http://phpids.org/
 * @package PHPIDS
 * @license LGPL
 * @since 2012/02/27
 * @version 0.7.1.4
 */
class phpidsAutoupdate {
  const FILENAME_RULES = 'default_filter.xml';
  const FILENAME_CONVERTER = 'Converter.php';
       
  /**
   * Url returns SHA-1 Hash of the current version
   * @var string
   */
  const HASH_BASE_URL = 'https://phpids.org/hash.php?f=';
 
  /**
   * The base url of the rules and converter file
   * @var string
   */
  const DOWNLOAD_BASE_URL = 'https://dev.itratos.de/projects/php-ids/repository/raw/trunk/lib/IDS/';
 
  /**
   * Base URL for retreiving last modification information. %s -> filename
   * @var string
   */
  const FEED_BASE_URL = 'https://dev.itratos.de/projects/php-ids/repository/revisions/trunk/lib/IDS?format=atom';
 
  /**
   * Path to phpids library
   * @var string
   */
  private $phpids_base;
 
  /**
   * Cache for remote file hashes
   * @var string
   */
  private $hash_cache;
  
  /**
   * @var Object Object contains the language object with translations
   */
  private $_oTranslation = null;


  /**
   * Contructor ot the auto update
   * 
   * @param string $basePath The base path to PHPIDS.
   * @param string $sTranslation The current name of the language class.
   */
  public function __construct ($basePath, $sTranslation) {
        
    $this->phpids_base = $basePath . '/IDS/';
    $this->hash_cache = array();
    
    $this->_oTranslation = new $sTranslation;
  } // __construct
 
  /**
   * Open the URL
   * @param string $url The URL string, that should be opened
   */
  private function fetchUrl($url) {
    global $modx;
    
    $result = false;
      
    $curl = curl_init();

    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
 
    // phpids is using cacert
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

    $data = curl_exec($curl);
 
    if ($data === false) {
      $modx->logEvent(0, 2, sprintf($this->_oTranslation->translate('message_error_autoupdate'), curl_error($curl)), 'PHPIDS Plugin');
      
      // No connection established, return false
      $result = false;
    } else {
      curl_close($curl);

      $result = $data;
    }
    
    return $result;
  } // fetchUrl
       
  /**
   * Perform a Rule and Converter update if necessary
   *
   * @return array result, message
   */
  public function update() {
    $result = array(
                  'result' => true
                , 'message' => '');
         
    // perform updates...
    $result = $this->updateFile(self::FILENAME_RULES);
         
    if ($result) {
      $result = $this->updateFile(self::FILENAME_CONVERTER);
    }
 
    return $result;
  } // update
       
  /**
   * Download current file and replaces local
   *
   * @param string FILENAME_RULES or FILENAME_CONVERTER
   * @return array result, message
   */
  private function updateFile($filename) {
    global $modx;

    $result = array(
                  'result' => true
                , 'message' => '');
    
    // fetch remote file:
    $file_contents = $this->fetchUrl(self::DOWNLOAD_BASE_URL.$filename);
 
    if ($file_contents === false) {
      $result['result'] = false;
    } else {
      if (sha1($file_contents) != $this->getCurrentFileHash($filename)) {
        $result['message'] = sprintf($this->_oTranslation->translate('message_error_updatedownload'), $filename, sha1($file_contents), $this->getCurrentFileHash($filename));
        $modx->logEvent(0, 2, $result['message'], 'PHPIDS Plugin');
        $result['result'] = false;
      } else {
        if (strlen($file_contents) <= 100) {
          $result['result'] = false;
        } else {
          // overwrite file contents
          if (!file_put_contents($this->phpids_base.$filename, $file_contents)) {
            $result['result'] = false;
          }
        }
      }
    }
  
    return $result;
  } // update
 
  /**
   * Retreive current SHA-1 hash from php-ids.org
   *
   * @param string FILENAME_RULES or FILENAME_CONVERTER
   * @return mixed SHA-1 hash or false if unavailible
   */
  private function getCurrentFileHash($filename) {
    $result = null;
    
    if (!empty($hash_cache[$filename])) {
      $result = $hash_cache[$filename];
    } else {

      $url = self::HASH_BASE_URL . $filename;

      $hash_response = $this->fetchUrl($url);

      if (!empty($hash_response)) {
        // phpids.org answered
        if ($hash_response === false) {
          $result = false;
        } else {
          $hash = trim($hash_response);

          if (preg_match("/^[0-9a-f]{40}$/", $hash)) {
            $hash_cache[$filename] = $hash;
            $result = $hash;
          } else {
            $result = false;
          }
        }
      } else {
        // phpids.org returned an empty result
        $result = true;
      }
    }

    return $result;
  } // getCurrentFileHash
 
  /**
   * Generate SHA-1 hash for local files
   *
   * @param string FILENAME_RULES or FILENAME_CONVERTER
   * @return mixed SHA-1 hash of local file or false if file does not exists
   */
  private function getLocalFileHash($filename) {
    $result = false;
    
    $path = $this->phpids_base . $filename;
    if (file_exists($path)) {
      $result = sha1_file($path);
    }
    
    return $result;
  } // getLocalFileHash
 
  /**
   * Compare local and remote version of ids rules.
   *
   * @return boolean returns true if rules are uptodate.
   */
  public function isRulesUpdated() {
    $result = false;
    
    if ($this->getCurrentFileHash(self::FILENAME_RULES) ==
      $this->getLocalFileHash(self::FILENAME_RULES)) {
      $result = true;
    }
    
    return $result;
  }
 
  /**
   * Compare local and remote version of ids converter.
   *
   * @return boolean returns true if rules are uptodate.
   */
  public function isConverterUpdated() {
    $result = false;
    
    if ($this->getCurrentFileHash(self::FILENAME_CONVERTER) ==
      $this->getLocalFileHash(self::FILENAME_CONVERTER)) {
      $result = true;
    }
    
    return $result;
  } // isConverterUpdated
 
  /**
   * Check for existing rules and converter and for write permissions
   *
   * @return boolean returns true if both files are writable
   */
  public function isWritable() {
    $result = false;
    
    if (file_exists($this->phpids_base.self::FILENAME_RULES) &&
      is_writable($this->phpids_base.self::FILENAME_RULES) &&
      file_exists($this->phpids_base.self::FILENAME_CONVERTER) &&
      is_writable($this->phpids_base.self::FILENAME_CONVERTER)) {
      $result = true;
    }
         
    return $result;
  } // isWritable
 
  /**
   * Returns a date string with last time the rules file was modified
   * @return string
   */
  private function getLastRulesUpdate() {
    return date($this->_oTranslation->translate('date_time_format'), filectime($this->phpids_base.self::FILENAME_RULES));
  } // getLastRulesUpdate
 
  /**
   * Returns a date string with last time the rules file was modified
   * @return string
   */
  private function getLastConverterUpdate() {
    return date($this->_oTranslation->translate('date_time_format'), filectime($this->phpids_base.self::FILENAME_CONVERTER));
  } // getLastConverterUpdate
 
  /**
   * Show version status table
   * 
   * @param $targetAddress string The address, for the update button, this could
   *                              be a complete address or a JavaScript function
   *                              call.
   * @return string Returns the current update status as HTML
   */
  public function showVersionStatus($targetAddress) {
    $update_needed = false;
 
    $result = '<table class="tableBorder">';
    $result .= '<tr><td class="tableHead" colspan="2"><h2>' . $this->_oTranslation->translate('caption_ids_version') . '</h2></td></tr>';
 
    $result .= '<tr><td class="tableCell" valign="top">' . $this->_oTranslation->translate('caption_update_filter') . '</td><td class="tableCell">';
    
    if ($this->isRulesUpdated()) {
      $result .=  '<span style="color: green;">' . $this->_oTranslation->translate('caption_update_state_ok') . '</span>';
    } else {
      $result .=  '<span style="color: red;">' . $this->_oTranslation->translate('caption_update_state_not_ok') . '</span>';
      $update_needed = true;
    }
    
    $result .= '<br />' . sprintf($this->_oTranslation->translate('caption_last_update_local'), $this->getLastRulesUpdate()) . '<br />';
    $result .= sprintf($this->_oTranslation->translate('caption_last_update_phpids'), $this->getLastFileUpdate(self::FILENAME_RULES)) . '<br />';
    $result .= sprintf($this->_oTranslation->translate('caption_hash'), $this->getLocalFileHash(self::FILENAME_RULES));
    
    if (!$this->isRulesUpdated()) {
      $result .= sprintf($this->_oTranslation->translate('caption_local_remote'), $this->getCurrentFileHash(self::FILENAME_RULES));
    }
    
    $result .= '</td></tr>';
         
    $result .= '<tr><td class="tableCell" valign="top">' . $this->_oTranslation->translate('caption_converter') . '</td><td class="tableCell">';
    
    if ($this->isConverterUpdated()) {
      $result .=  '<span style="color: green;">' . $this->_oTranslation->translate('caption_update_state_ok') . '</span>';
    } else {
      $result .=  '<span style="color: red;">' . $this->_oTranslation->translate('caption_update_state_not_ok') . '</span>';
      $update_needed = true;
    }
    
    $result .= '<br />' . sprintf($this->_oTranslation->translate('caption_last_update_local'), $this->getLastConverterUpdate()) . '<br />';
    $result .= sprintf($this->_oTranslation->translate('caption_last_update_phpids'), $this->getLastFileUpdate(self::FILENAME_CONVERTER)) . '<br />';
    $result .= sprintf($this->_oTranslation->translate('caption_hash'), $this->getLocalFileHash(self::FILENAME_CONVERTER));
    
    if (!$this->isConverterUpdated()) {
      $result .= sprintf($this->_oTranslation->translate('caption_local_remote'), $this->getCurrentFileHash(self::FILENAME_CONVERTER));
    }
    $result .= '</td></tr>';
 
    // is update possible?
    if (!$this->isRulesUpdated() || !$this->isConverterUpdated()) {
      $result .= '<tr><td class="tableCell"> </td><td class="tableCell">';
      if ($this->isWritable() && function_exists('curl_init')) {
        $result .= '<input type="button" name="update_phpids" value="'. $this->_oTranslation->translate('caption_run_update') . '"' . $targetAddress . ' />';
      } else {
        $result .= $this->_oTranslation->translate('caption_no_update_available');
      }
      $result .= '</td></tr>';
    }
 
    $result .= '</table>';
 
    return $result;
  } // showVersionStatus
       
  /**
   * Returns last
   *
   * @param string filename
   * @return mixed date of last change or if an error occured, false
   */
  private function getLastFileUpdate($filename) {
    $result = false;
    $feed_url = sprintf(self::FEED_BASE_URL, $filename);
 
    $content = $this->fetchUrl($feed_url);
    if (preg_match("/<pubDate>([^<]+)<\/pubDate>/", $content, $match)) {
      $result = date($this->_oTranslation->translate('date_time_format'), strtotime($match[1]));
    }
    
    return $result;
  } // getLastFileUpdate
} // phpidsAutoupdate
