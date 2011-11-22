<?php
/**
 * Handles all data requests from the module
 *
 * @name PHPIDS
 *
 * @author Stefanie Janine Stoelting, mail@stefanie-stoelting.de
 * @link http://code.google.com/p/phpids-for-modx/ Project home page
 * @link http://www.stefanie-stoelting.de/phpids-news.html
 * @link http://phpids.org/
 * @link http://jquery.com jQuery JavaScript libary
 * @link http://www.trirand.net/ jqGrid is a jQuery based table class
 * @link http://jquery.malsup.com/block/ The jQuery BlockUI Plugin lets you simulate synchronous behavior when using AJAX
 * @package PHPIDS
 * @license LGPL
 * @since 2011/11/22
 * @version 0.7.alpha.1
 */
class modulePHPIDS
{

  /**
   * Constant integer Defines getLogContent
   */
  const TYPE_GET_LOG_CONTENT = 0;

  /**
   * Constant integer Defines getLogData
   */
  const TYPE_GET_LOG_DATA = 1;

  /**
   * Constant integer Defines getBlockedData
   */
  const TYPE_GET_BLOCKED_DATA = 2;

  /**
   * Constant integer Defines blockIP
   */
  const TYPE_BLOCK_IP = 3;

  /**
   * Constant integer Defines unBlockIP
   */
  const TYPE_UNBLOCK_IP = 4;

  /**
   * Constant integer Defines the getLogIDDetail
   */
  const TYPE_GET_LOG_IDDETAIL = 6;

  /**
   * Constant integer Defines the getLogIDDetail
   */
  const TYPE_GET_BLOCK_IDDETAIL = 7;

  /**
   * Constant integer Defines the emptyLogTable
   */
  const TYPE_TRUNCATE_LOG = 8;

  /**
   * Constant integer Defines the deleteLogRecord
   */
  const TYPE_DELETE_LOG_RECORD = 9;

  /**
   * Constant integer Defines the updateDefaultFilter
   */
  const TYPE_UPDATE_FILTER = 10;

  /**
   * Constant integer Defines the exportIntrusionsAsCSV
   */
  const TYPE_GET_CSV = 11;

  /**
   * Constant string Default language is English
   */
  const DEFAULT_LANGUAGE = 'english';

  /**
   * Constant float DEFAULT_FONT_SIZE The default is 0.8, all values are used as em
   */
  const DEFAULT_FONT_SIZE = 0.8;

  /**
   * Constant float DEFAULT_FONT_SIZE_DIALOG The default is 0.6, all values are used as em
   */
  const DEFAULT_FONT_SIZE_DIALOG = 0.6;

  /**
   * Constant int Default row count of the tables
   */
  const DEFAULT_ROW_COUNT = 20;

  /**
   * Constant string field separator
   */
  const FIELD_SEPARATOR = '|';

  /**
   * Constant string table logdata ID
   */
  const  TABLE_ID = 'table_logdata';

  /**
   * Constant string table pager id
   */
  const PAGER_ID = 'div_pager';

  /**
   * Constant string with the uri to the PHPIDS filter RSS
   */
  const PHPIDS_FILTER_RSS_URI = 'https://trac.phpids.org/index.fcgi/log/trunk/lib/IDS/default_filter.xml?limit=1&format=rss';

  /**
   * Constant string with the uri to the current PHPIDS default filter
   */
  const PHPIDS_DEFAULT_FILTER_URI = 'https://dev.itratos.de/projects/php-ids/repository/raw/trunk/lib/IDS/default_filter.xml';
  
  /**
   * Constant string with the location of the PHPIDS test URI
   */
  const PHPIDS_TEST_URI = 'http://demo.phpids.org/?test=%s';

  /**
   * Constant string with the uri to the news
   */
  const NEWS_RSS_URI = 'http://www.stefanie-stoelting.de/phpids-news-rss-feed.html';

  /**
   * Constant string name of the intrusion table
   */
  const TABLE_NAME_INTRUSIONS = 'phpids_intrusions';

  /**
   * Constant string name of the block table
   */
  const TABLE_NAME_BLOCK = 'phpids_block';

  /**
   * Constant string name of the options table
   */
  const TABLE_NAME_OPTIONS = 'phpids_options';

  /**
   * Constant string Contains the ripe address to check the IP addresses
   */
  const RIPE = 'http://www.db.ripe.net/whois?form_type=simple&full_query_string=&searchtext=%s&do_search=Search';
  
  /**
   * Constant string contains the infosniper address to check the IP addresses
   */
  const INFOSNIPER = 'http://www.infosniper.net/index.php?ip_address=%s';
  
  /**
   * Constant integer with the default with for modal windows
   */
  const DEFAULT_WINDOWS_WIDTH = 450;

  /**
   * @var Object Object contains the language object with translations
   */
  private $_oTranslation = null;

  /**
   * @var string Path to the module
   */
  private $_phpIDSPath = 'assets/modules/phpids/';

  /**
   * @var string current language
   */
  private $_language = 'english';

  /**
   * @var string URL for inlcuding CSS and JavaScript files in HTML headers
   */
  private $_sBaseURL = '';

  /**
   * @var string Default font size is 0.8em
   */
  private $_sFontSize = '';

  /**
   * @var string Default font size is 0.6em
   */
  private $_sFontSizeDialog = '';

  /**
   * @var int The initialized table row count
   */
  private $_iRowCount = 0;

  /**
   * @var array Translation from language name to language shortcut
   */
  private $_aLanguageShortcuts =
    array(
      'english'     => 'en',
      'german'      => 'de'
    );

  /**
   * @var string Path to the PHPIDS lib directory
   */
  private $_phpidsLibPath = '';

  /**
   * @var array Containing the table names with the real table names
   */
  private $_tableNames = array();

  /**
   * @var string The delimitier for the CSV export
   */
  private $_csvDelimiter = ',';

  /**
   * @var string The enclosure for the CSV export
   */
  private $_csvEnclosure = '#';
  
  /*
   * @var string The name of the current translation class
   */
  private $_translationClassName='';

    /**
   * Initalizing the class, include language file, setting default values
   *
   * @param string $language The language
   * @param string $fontSize The font size has to be a string numeric value
   * @param integer $DEFAULT_ROW_COUNT The default row count of tables
   * @param string $fontSizeDialog The default font size for dialogs
   * @param array $tableNames The names of the tables
   */
  function  __construct($language=self::DEFAULT_LANGUAGE,
          $fontSize=self::DEFAULT_FONT_SIZE,
          $DEFAULT_ROW_COUNT=self::DEFAULT_ROW_COUNT,
          $fontSizeDialog=self::DEFAULT_FONT_SIZE_DIALOG,
          $tableNames=array())
  {
    global $modx;

    try {
      $language = empty($language) ? self::DEFAULT_LANGUAGE : addslashes($language);
      $this->_sFontSize = is_numeric($fontSize) ? $fontSize : self::DEFAULT_FONT_SIZE;
      $this->_sFontSizeDialog = is_numeric($fontSizeDialog) ? $fontSizeDialog : self::DEFAULT_FONT_SIZE_DIALOG;
      $this->_iRowCount = is_numeric($DEFAULT_ROW_COUNT) ? $DEFAULT_ROW_COUNT : self::DEFAULT_ROW_COUNT;
      $this->_tableNames = $tableNames;

      /** Check existance of a language file for the given language, if not found, take the default language **/
      if (file_exists(MODX_BASE_PATH . 'assets/modules/phpids/lang/' . $language . '.inc.php')) {

        require_once(MODX_BASE_PATH . 'assets/modules/phpids/lang/' . $language . '.inc.php');

      } elseif (MODX_BASE_PATH . 'assets/modules/phpids/lang/' . self::DEFAULT_LANGUAGE . '.inc.php') {

        require_once(MODX_BASE_PATH . 'assets/modules/phpids/lang/' . self::DEFAULT_LANGUAGE . '.inc.php');
        $language = self::DEFAULT_LANGUAGE;

      } else {

        die ('No language file found!');

      }

      // Include the class files
      require_once('class.uitabs.php');
      require_once('class.uiforms.php');
      require_once('class.htmlinclude.php');
      require_once('class.html.php');
      require_once('class.rssentry.php');
      require_once('class.rssfeed.php');
      require_once('class.options.php');
      require_once('class.htmlbuttons.php');
      require_once('class.atomparser.php');
      require_once ('class.phpidsAutoupdate.php');

      $this->_translationClassName = 'Translation' . ucfirst($language);

      $this->_language = $language;

      $this->_oTranslation = new $this->_translationClassName;

      $this->_sBaseURL = $siteURL = $modx->config['site_url'];

      if (!substr($this->_sBaseURL, -1, 1) == '/') {
        $this->_sBaseURL .= '/';
      }

    } catch (Exception $e) {
      $this->logError($e);
    }
  } // __construct

  /**
   * Sets the table names with the prefixes, or not.
   *
   * @param array $tableNames Contains all table names
   * @throws If $tableNames is not an array
   */
  public function setTableNames($tableNames)
  {
    if (is_array($tableNames)) {
      $this->_tableNames = $tableNames;
    } else {
      throw new Exception('$tableNames is not an array.');
    }
  } // setTableNames

  /**
   * Sets the table names with the prefixes, or not.
   *
   * @param string id Contains the table identifier
   * @param string $name Contains the table name
   * @throws If $id or $name is empty
   */
  public function setTableName($id, $name)
  {
    if (!empty($id) && !empty($name)) {
      $this->_tableNames[$id] = $name;
    } else {
      throw new Exception('$id or $name is empty.');
    }
  } // setTableName

  /**
   * Sets the PHPIDS lib directory.
   * 
   * @param string $value The path to the PHPIDS lib directory
   */
  public function setPhpidsLibPath($value)
  {
    $this->_phpidsLibPath = $value;
  } // setPhpidsLibPath

  /**
   * Returns the PHPIDS lib directory.
   *
   * @return string The path to the PHPIDS lib directory
   */
  public function getPhpidsLibPath()
  {
    return $this->_phpidsLibPath;
  } // getPhpidsLibPath
  
  /**
   * Error handling, writes errors to the MODx system log
   *
   * @param object $oError Exception
   */
  public function logError($oError)
  {
    global $modx;

    try {
      $modx->logEvent(0, 3,
              sprintf($this->_oTranslation->translate('text_error_log'),
                      $oError->getFile,
                      $oError->getLine(),
                      $oError->getMessage()),
              $this->_oTranslation->translate('caption_error_log'));

    } catch (Exception $e) {
      die(
        sprintf('An error occured in file %1$s in line %2$d: %3$s',
                $oError->getFile,
                $oError->getLine(),
                $oError->getMessage())
      );
    }
  } // logError


  /**
   * Creates the table for blocking IP-addresses, if it does not exist
   *
   * @param string $sTableName
   */
  public function createBlockTable($sTableName)
  {
    global $modx;

    try {
      $sSQL = 'CREATE TABLE IF NOT EXISTS `' . mysql_real_escape_string($sTableName) . '` (
                `id` int(11) unsigned NOT null auto_increment,
                `ip` varchar(15) character set utf8 collate utf8_bin NOT null,
                `createdby` varchar(100) character set utf8 collate utf8_bin NOT null,
                `created` datetime NOT null,
                PRIMARY KEY  (`id`)
              ) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin ;';

      $modx->db->query($sSQL);

    } catch (Exception $e) {
      $this->logError($e);
    }
  } // createBlockTable


  /**
   * Creates the table for logging intrusions, if it does not exist
   *
   * @param string $sTableName
   */
  public function createLogTable($sTableName)
  {
    global $modx;

    try {
      $sSQL = 'CREATE TABLE IF NOT EXISTS `' . mysql_real_escape_string($sTableName) . '` (
                `id` int(11) unsigned NOT null auto_increment,
                `name` varchar(128) character set utf8 collate utf8_bin NOT null,
                `value` text character set utf8 collate utf8_bin NOT null,
                `page` varchar(255) character set utf8 collate utf8_bin NOT null,
                `tags` varchar(128) character set utf8 collate utf8_bin NOT null,
                `ip` varchar(15) character set utf8 collate utf8_bin NOT null,
                `impact` int(11) unsigned NOT null,
                `origin` varchar(15) character set utf8 collate utf8_bin NOT null,
                `created` datetime NOT null,
                PRIMARY KEY  (`id`)
              ) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin ;';

      $modx->db->query($sSQL);
      $this->updateLogTable($sTableName);

    } catch (Exception $e) {
      $this->logError($e);
    }
  } // createLogTable
  
  /**
   * Update the log table.
   *
   * @param string $sTableName
   */
  public function updateLogTable($sTableName)
  {
    global $modx;

    try {
      $sSQL = 'SELECT COUNT(*) ColumnExists '
             .'FROM information_schema.COLUMNS '
             .'WHERE TABLE_SCHEMA = \'' . $modx->db->config['dbase'] . '\' '
             .'AND TABLE_NAME = \'' . $sTableName . '\' '
             .'AND COLUMN_NAME = \'ip2\'';

    $rRecordset =  $modx->db->query($sSQL);
    if ($row = mysql_fetch_object($rRecordset)) {
        if ($row->ColumnExists == 1) {
            $sSQL = 'ALTER TABLE ' . $sTableName . ' ADD ip2 VARCHAR(15) NOT NULL AFTER ip';
            $modx->db->query($sSQL);
        }
    }

    } catch (Exception $e) {
      $this->logError($e);
    }
  } // updateLogTable

    /**
   * Returns the complete HTML
   *
   * @param string $sJavaScript
   * @param string $sTableID
   * @param string $sPagerID
   * @param int $iModuleID The module ID of MODx for PHPIDS
   * @return string
   */
  private function getHTML($sJavaScript, $sTableID, $sPagerID, $iModuleID)
  {

    try {
      $sJSphpIDSPathFile = 'js/i18n/grid.locale-'
                         . $this->getLanguageShortcut($this->_language)
                         . '.js';

      if (!file_exists(MODX_BASE_PATH . $this->_phpIDSPath . $sJSphpIDSPathFile)) {
        $sJSphpIDSPathFile = 'js/i18n/grid.locale-'
                           . $this->getLanguageShortcut($this->_language)
                           . '.js';
      }

      // Create the HTML include variable for easier including CSS and JavaScript files
      $htmlInclude = new HtmlInclude($this->_sBaseURL . $this->_phpIDSPath);
      $uiTabs = new UITabs();
      $html = new HTML();

      $uiTabs->setFontSize('10pt');
      $uiTabs->setTabFontSize('11pt');

      // The log data tab
      $uiTabs->setTab(1,
             $this->_oTranslation->translate('caption_tab_log_data'),
             "        <table id=\"" . $sTableID . "\"></table>\n"
            ."        <div id=\"" . $sPagerID . "\"></div>\n");

      // The block data tab
      $uiTabs->setTab(2,
             $this->_oTranslation->translate('caption_tab_blocked_data'),
             "        <table id=\"" . $sTableID . "2\"></table>\n"
            ."        <div id=\"" . $sPagerID . "2\"></div>\n");

      // The extended tab
      $tab3Content = $this->getFilterButton()
                    .$this->getDownloadLink($iModuleID)
                    .$this->getTruncateTable();
      
      $uiTabs->setTab(3,
             $this->_oTranslation->translate('caption_tab_extended'),
             $tab3Content);

      /*
      // The news tab
      $uiTabs->setTab(4,
              $this->_oTranslation->translate('caption_news'),
              $this->getNewsList());
       * 
       */

      $result  = $html->getDocTypeXHTMLTransitional()
                ."<head>\n"
                .$html->getTitle('PHPIDS')
                .$html->getMetaContentType()
                .$htmlInclude->getInclude(HtmlInclude::CSS, 'css/ui.jqgrid.css')
                .$htmlInclude->getInclude(HtmlInclude::CSS, 'css/jquery-ui-1.8.16.custom.css')
                ."\n"
                .$htmlInclude->getInclude(HtmlInclude::JAVASCRIPT, 'js/jquery-1.7.min.js')
                .$htmlInclude->getInclude(HtmlInclude::JAVASCRIPT, $sJSphpIDSPathFile)
                .$htmlInclude->getInclude(HtmlInclude::JAVASCRIPT, 'js/jquery.jqGrid-4.2.0.min.js')
                .$htmlInclude->getInclude(HtmlInclude::JAVASCRIPT, 'js/jquery-ui-1.8.16.custom.min.js')
                .$htmlInclude->getInclude(HtmlInclude::JAVASCRIPT, 'js/jquery.blockUI.js')
                .$sJavaScript
                ."</head>\n"
                ."<body>\n"
                .$uiTabs->getTab()
                ."\n"
                ."  <div id=\"dialog\" title=\"" . $this->_oTranslation->translate('title_confirmation') . "\">\n"
                ."  </div>\n"
                ."</body>\n"
                .'</html>';

      return $result;

    } catch (Exception $e) {
      $this->logError($e);
    }
  } // getHTML

  
  /**
   *
   * @param integer $iID The record id
   * @param string $sIP The IP address
   * @param string $sAddress The AJAX address
   * @param string $sButtonCaption The button caption
   * @return string The block data, that will be presented by JavaScript
   */
  private function getBlockForm($iID, $sIP, $sAddress, $sButtonCaption)
  {
    try {
      $result = $iID . self::FIELD_SEPARATOR .  $sIP . self::FIELD_SEPARATOR 
               . $sAddress . self::FIELD_SEPARATOR .$sButtonCaption;

      return $result;

    } catch (Exception $e) {
      $this->logError($e);
    }
  } // getBlockForm

  
  /**
   * Returns all standard information of a table
   * 
   * @param string $sTableName
   * @return object iRecordCount, iTotalPages, iPage, iLimit, iStart
   */
  private function getTableInfos($sTableName)
  {
    global $modx;

    $result = null;

    try {
      $sSQL = 'SELECT COUNT(*) iRecordCount '
            .'FROM ' . mysql_real_escape_string($sTableName);

      $rRecordset =  $modx->db->query($sSQL);
      $row = mysql_fetch_object($rRecordset);
      if ($row) {
        $result->iRecordCount = $row->iRecordCount;
      } else {
        $result->iRecordCount = 0;
      }

      if ($result->iRecordCount > 0 && $this->getLimit() > 0) {
        $result->iTotalPages = ceil($result->iRecordCount / $this->getLimit());
      } else {
        $result->iTotalPages = 0;
      }

      $result->iPage = $this->getPage();
      if ($result->iPage > $result->iTotalPages) {
        $result->iPage = $result->iTotalPages;
      }

      $result->iLimit = $this->getLimit();
      $result->iStart = $result->iLimit * $result->iPage - $result->iLimit;
      if ($result->iStart < 0) {
        $result->iStart = 0;
      }

      return $result;

    } catch (Exception $e) {
      $this->logError($e);
    }
  } // getTableInfos


  /**
   *
   * @return integer page 
   */
  private function getPage()
  {
    try {
      return isset($_GET['page']) ? (is_numeric($_GET['page']) ? $_GET['page'] : 1) : 1;
    } catch (Exception $e) {
      $this->logError($e);
    }
  } // getPage


  /**
   *
   * @return integer limit
   */
  private function getLimit()
  {
    try {
      return (isset($_GET['rows']) && is_numeric($_GET['rows'])) ?
                    ($_GET['rows'] < 0 ? $_GET['rows'] * (-1) : $_GET['rows']) : 10;
    } catch (Exception $e) {
      $this->logError($e);
    }
  } // getLimit


  /**
   * Returns the sort field, if the given field matches to the allowed ones
   *
   * @return string sort index
   */
  private function getSortIndex()
  {
    try {
      $result = isset($_GET['sidx']) ? addslashes($_GET['sidx']) : 1;

      if ($result != 1) {
        $aAvailableColums =
          array(
            'id',
            'name',
            'value',
            'page',
            'tags',
            'ip',
            'impact',
            'origin',
            'created',
            'createdby'
          );

        if (array_search(strtolower($result), $aAvailableColums)) {
          $result = '`' . mysql_real_escape_string(strtolower($result)) . '`';
        } else {
          $result = 1;
        }
      }

      return $result;
    } catch (Exception $e) {
      $this->logError($e);
    }
  } // getSortIndex


  /**
   *
   * @return string sort order
   */
  private function getSortOrder()
  {
    try {
    $result = isset($_GET['sord']) ? stripslashes($_GET['sord']) : 0;
      if (strtoupper($result) != 'DESC') {
        $result = 'ASC';
      }

      return $result;
    } catch (Exception $e) {
      $this->logError($e);
    }
  } // getSortOrder

  /**
   *
   * @return integer sort index
   */
  private function getLogID()
  {
    try {
      return (isset($_GET['logid']) && is_numeric($_GET['logid'])) ? $_GET['logid'] : 0;
    } catch (Exception $e) {
      $this->logError($e);
    }
  } // getSortIndex


  /**
   * Returns the language shortcut for the given language. If the language is
   * not found, the shortcut of default language is taken
   *
   * @param string $language
   * @return string
   */
  public function getLanguageShortcut($language)
  {
    try {
      $result = $this->_aLanguageShortcuts[$language];

      if (empty($result)) {
        $result = $this->_aLanguageShortcuts[self::DEFAULT_LANGUAGE];
      }

      return $result;
    } catch (Exception $e) {
      $this->logError($e);
    }
  } // getLanguageShortcut


  /**
   * Returns a HTML page with the given error message
   *
   * @param string $sErrorText
   * @return string
   */
  public function getErrorContent($sErrorText)
  {
    try {
      $html = new HTML();
      $result  = $html->getDocTypeXHTMLTransitional()
                ."<head>\n"
                .$html->getTitle('PHPIDS')
                .$html->getMetaContentType()
                ."</head>\n"
                ."<body>\n"
                ."  <div id=\"content\">\n"
                ."    <h1>" . $this->_oTranslation->translate('h1_Error detected') . "</h1>\n"
                .'    <p>' . $sErrorText . "</p>\n"
                ."  </div>\n"
                ."</body>\n"
                .'</html>';

      return $result;
    } catch (Exception $e) {
      $this->logError($e);
    }
  } // getErrorContent

  /**
   * Returns the block form JavaScript code
   *
   * @param int $iModuleID The module ID of MODx for PHPIDS
   * @return string The block form
   */
  private function getFormBlock($iModuleID)
  {
    $uiForm = new UIForms();
    $html = new HTML();

    $uiForm->setFunctionName('BlockIPModal(iLogID)');
    $uiForm->setFontSize($this->_sFontSizeDialog);
    $uiForm->setModal(true);
    $uiForm->setResizeble(false);
    $uiForm->setWidth(self::DEFAULT_WINDOWS_WIDTH);
    $uiForm->addButton('button_block_ip',
            $this->_oTranslation->translate('button_block_ip'),
            "              \$.get('" . $this->_sBaseURL
                                      . "manager/index.php', {'a':112, 'id':$iModuleID, 'getdata':"
                                . self::TYPE_BLOCK_IP
                                . ", 'ipaddress':$('#ipaddress').val()}, function(data) {\n"
           ."                alert(data);\n"
           ."                $(\"#" . self::TABLE_ID . "2\").trigger(\"reloadGrid\");\n"
           ."              });\n"
           ."              $(this).dialog(\"close\");\n");
    $uiForm->addButton('button_close',
            $this->_oTranslation->translate('button_close'),
            "              $(this).dialog(\"close\");\n");
    $uiForm->setJSONaddress($this->_sBaseURL . 'manager/index.php');
    $uiForm->setJSONparameter('a', '112');
    $uiForm->setJSONparameter('id', $iModuleID);
    $uiForm->setJSONparameter('getdata', self::TYPE_GET_LOG_IDDETAIL);
    $uiForm->setJSONparameter('logid', 'iLogID');
    $uiForm->setFormContent("'<h2>"
                              . $this->_oTranslation->translate('caption_ID')
                              . " ' + data.iIDLog + '<h2>' + \n"
                  ."                     '<form id=\"submitdata\">' + \n"
                  // Get the form fields
                  .$html->getFormField($html->getTYPE_TEXT(),
                          $this->_oTranslation->translate('caption_name'),
                          'name', true)
                  .$html->getFormField($html->getTYPE_TEXT_AREA(),
                          $this->_oTranslation->translate('caption_value'),
                          'value', true)
                  .$html->getFormField($html->getTYPE_TEXT(),
                          $this->_oTranslation->translate('caption_page'),
                          'page', true)
                  .$html->getFormField($html->getTYPE_TEXT(),
                          $this->_oTranslation->translate('caption_ip'),
                          'ip', false, 'ipaddress')
                  .$html->getFormField($html->getTYPE_TEXT(),
                          $this->_oTranslation->translate('caption_impact'),
                          'impact', true)
                  .$html->getFormField($html->getTYPE_TEXT(),
                          $this->_oTranslation->translate('caption_origin'),
                          'origin', true)
                  .$html->getFormField($html->getTYPE_TEXT(),
                          $this->_oTranslation->translate('caption_created'),
                          'created', true)
                  ."                      '</form>';\n"
                  ."\n");

    return $uiForm->getFormJSON();
  } // getFormBlock

  /**
   * Returns the unblock form JavaScript code
   *
   * @param int $iModuleID The module ID of MODx for PHPIDS
   * @return string The unblock form
   */
  private function getFormUnBlock($iModuleID)
  {
    $uiForm = new UIForms();
    $html = new HTML();

    $uiForm->setFunctionName('UnBlockIPModal(iIDBlock)');
    $uiForm->setFontSize($this->_sFontSizeDialog);
    $uiForm->setModal(true);
    $uiForm->setResizeble(false);
    $uiForm->setWidth(self::DEFAULT_WINDOWS_WIDTH);
    $uiForm->addButton('button_unblock_ip',
            $this->_oTranslation->translate('button_unblock_ip'),
            "              \$.get('" . $this->_sBaseURL
                                      . "manager/index.php', {'a':112, 'id':$iModuleID, 'getdata':"
                                . self::TYPE_UNBLOCK_IP
                                . ", 'ipaddress': $('#ipaddress').val()}, function(data) {\n"
           ."                alert(data);\n"
           ."                $(\"#" . self::TABLE_ID . "2\").trigger(\"reloadGrid\");\n"
           ."              });\n"
           ."              $(this).dialog(\"close\");\n");
    $uiForm->addButton('button_close',
            $this->_oTranslation->translate('button_close'),
            "              $(this).dialog(\"close\");\n");
    $uiForm->setJSONaddress($this->_sBaseURL . 'manager/index.php');
    $uiForm->setJSONparameter('a', '112');
    $uiForm->setJSONparameter('id', $iModuleID);
    $uiForm->setJSONparameter('getdata', self::TYPE_GET_BLOCK_IDDETAIL);
    $uiForm->setJSONparameter('logid', 'iIDBlock');
    $uiForm->setFormContent("'<h2>"
                              . $this->_oTranslation->translate('caption_ID')
                              . " ' + data.iIDLog + '<h2>' + \n"
                  ."                     '<form id=\"submitdata\">' + \n"
                  // Get the form fields
                    .$html->getFormField($html->getTYPE_TEXT(),
                            $this->_oTranslation->translate('caption_ip'),
                            'ip', true, 'ipaddress')
                    .$html->getFormField($html->getTYPE_TEXT(),
                            $this->_oTranslation->translate('caption_createdby'),
                            'createdby', true)
                    .$html->getFormField($html->getTYPE_TEXT(),
                            $this->_oTranslation->translate('caption_created'),
                            'created', true)
                  ."                      '</form>';\n"
                  ."\n");

    return $uiForm->getFormJSON();
  } // getFormUnBlock

  /**
   * Returns the unblock form JavaScript code
   *
   * @param int $iModuleID The module ID of MODx for PHPIDS
   * @return string The unblock form
   */
  private function getFormTruncateLogTable($iModuleID)
  {
    $uiForm = new UIForms();
    $html = new HTML();

    $uiForm->setFunctionName('ConfirmTruncateLog()');
    $uiForm->setFontSize($this->_sFontSizeDialog);
    $uiForm->setModal(true);
    $uiForm->setResizeble(false);
    $uiForm->setWidth(350);
    $uiForm->setHeight(140);
    $uiForm->addButton('button_empty_log_table',
            $this->_oTranslation->translate('button_empty_log_table'),
            "              \$.get('" . $this->_sBaseURL
                                      . "manager/index.php', {'a':112, 'id':$iModuleID, 'getdata':"
                                . self::TYPE_TRUNCATE_LOG
                                . "}, function(data) {\n"
           ."                alert(data);\n"
           ."                $(\"#" . self::TABLE_ID . "2\").trigger(\"reloadGrid\");\n"
           ."              });\n"
           ."              $(this).dialog(\"close\");\n");
    $uiForm->addButton('button_close',
            $this->_oTranslation->translate('button_close'),
            "              $(this).dialog(\"close\");\n");
    $uiForm->setFormContent('\'<h2>'
                              .$this->_oTranslation->translate('message_truncate_log_confirm')
                              ." <h2>' +\n"
                              .'\'<p>'
                              .$this->_oTranslation->translate('message_truncate_log')
                              ."</p>'\n");

    return $uiForm->getForm();
  } // getFormTruncateLogTable

  /**
   * Returns the document with the log data table
   *
   * @param int $iModuleID The module ID of MODx for PHPIDS
   * @return string
   */
  public function getLogContent($iModuleID)
  {
    global $modx;

    try {
      $html = new HTML();
      $iModuleID = is_numeric($iModuleID) ? $iModuleID : 0;

      $sJSFunctionBlockIPModal = 'BlockIPModal';
      $sJSFunctionUnBlockIPModal = 'UnBlockIPModal';

      // JavaSript is inserted in PHP because language settings and configurations
      // of MODx are available
      $sJavaScript = "  <script type=\"text/javascript\"  charset=\"utf-8\">\n"
                    // Function for calling the block IP modal div
                    .$this->getFormBlock($iModuleID)
                    ."\n"
                    // Function for calling the unblock IP modal div
                    .$this->getFormUnBlock($iModuleID)
                    ."\n"
                    // Function for truncate the log table
                    .$this->getFormTruncateLogTable($iModuleID)
                    ."\n"
                    // Function for getting the Log data
                    ."    function insertModalLog(cellValue, options, rowObject) {\n"
                    ."      var htmlData = cellValue.split('" . self::FIELD_SEPARATOR . "');\n"
                    ."      var result = '<button onClick=\""
                                . $sJSFunctionBlockIPModal
                                . "(' + htmlData[0] + ');\">' + htmlData[3] + '</button>' \n"
                    ."      return result;\n"
                    ."    }\n"
                    ."\n"
                    // Function for getting the blocked data
                    ."    function insertModalBlock(cellValue, options, rowObject) {\n"
                    ."      var htmlData = cellValue.split('" . self::FIELD_SEPARATOR . "');\n"
                    ."      var result = '<button onClick=\""
                                . $sJSFunctionUnBlockIPModal
                                . "(' + htmlData[0] + ');\">' + htmlData[3] + '</button>' \n"
                    ."      return result;\n"
                    ."    }\n"
                    ."\n"
                    ."    function updateFilter() {\n"
                    ."      \$.blockUI({message: '<h3>"
                    .     $this->_oTranslation->translate('caption_truncate_log_progress')
                    .     " <img src=\"" . $this->_sBaseURL . $this->_phpIDSPath . "css/images/ui-anim_basic_16x16.gif\" /></h3>'});\n"
                    ."      \$.get('" . $this->_sBaseURL
                    .         "manager/index.php', {'a':112, 'id':$iModuleID, 'getdata':"
                                . self::TYPE_UPDATE_FILTER . "} , function(data) {\n"
                    ."        $('#FilterUpdate').html(data);\n"
                    ."        $.unblockUI();\n"
                    ."      });\n"
                    ."    }\n"
                    ."\n"
                    ."    function emptyLogTable() {\n"
                    ."      confirmed = confirm('" . $this->_oTranslation->translate('message_truncate_log_confirm') . "');\n"
                    ."      if (confirmed == true) {\n"
                    ."        \$.blockUI({message: '<h3>"
                    .       $this->_oTranslation->translate('caption_update_filter_progress')
                    .       " <img src=\"" . $this->_sBaseURL . $this->_phpIDSPath . "css/images/ui-anim_basic_16x16.gif\" /></h3>'});\n"
                    ."        \$.get('" . $this->_sBaseURL
                    .           "manager/index.php', {'a':112, 'id':$iModuleID, 'getdata':"
                                  . self::TYPE_TRUNCATE_LOG . "} , function(data) {\n"
                    ."          $('#TruncateTable').html(data);\n"
                    ."          $(\"#" . self::TABLE_ID . "\").trigger(\"reloadGrid\");\n"
                    ."          $.unblockUI();\n"
                    ."        });\n"
                    ."      }\n"
                    ."    }\n"
                    ."\n"
                    ."    $(document).ready(function() {\n"
                    // Initialize the dialog box
                    ."      $(\"#dialog\").dialog({\n"
                    ."        autoOpen: false,\n"
                    ."        modal: true\n"
                    ."      });\n"
                    ."\n"
                    // Initialize the tabs
                    ."    	$(function() {\n"
                    ."        $(\"#tabs\").tabs();\n"
                    ."      });"
                    ."\n"
                    ."    $(\"#dialog\").dialog({\n"
                    ."      buttons : {\n"
                    ."        \"" . $this->_oTranslation->translate('button_block_ip') . "\" : function() {\n"
                    ."          window.location.href = targetUrl;\n"
                    ."        },\n"
                    ."        \"" . $this->_oTranslation->translate('button_close') . "\" : function() {\n"
                    ."          $(this).dialog(\"close\");\n"
                    ."        }\n"
                    ."      }\n"
                    ."    });\n"
                    ."\n"
                    // Initialize the log data table
                    ."      jQuery(\"#" . self::TABLE_ID . "\").jqGrid({\n"
                    ."        url:'" . $this->_sBaseURL 
                                . "manager/index.php?a=112&id=$iModuleID&getdata="
                                . self::TYPE_GET_LOG_DATA . "',\n"
                    // Defining data source type
                    ."        datatype: \"json\",\n"
                    // Setting column captions
                    ."        colNames:[\n"
                    ."          '" . $this->_oTranslation->translate('caption_ID') . "',\n"
                    ."          '" . $this->_oTranslation->translate('caption_name') . "',\n"
                    ."          '" . $this->_oTranslation->translate('caption_value') . "',\n"
                    ."          '" . $this->_oTranslation->translate('caption_page') . "',\n"
                    ."          '" . $this->_oTranslation->translate('caption_ip') . "',\n"
                    ."          '" . $this->_oTranslation->translate('caption_impact') . "',\n"
                    ."          '" . $this->_oTranslation->translate('caption_origin') . "',\n"
                    ."          '" . $this->_oTranslation->translate('caption_created') . "',\n"
                    ."          '" . $this->_oTranslation->translate('caption_block_button') . "'\n"
                    ."        ],\n"
                    // Define the columns
                    ."        colModel:[\n"
                    ."          {name:'id',index:'id', width:60 * $this->_sFontSize, sorttype:\"int\"},\n"
                    ."          {name:'name',index:'name', width:150 * $this->_sFontSize},\n"
                    ."          {name:'value',index:'value', width:300 * $this->_sFontSize},\n"
                    ."          {name:'page',index:'page', width:300 * $this->_sFontSize},\n"
                    ."          {name:'ip',index:'ip', width:130 * $this->_sFontSize},\n"
                    ."          {name:'impact',index:'impact', width:45 * $this->_sFontSize, sorttype:\"int\"},\n"
                    ."          {name:'origin',index:'origin', width:130 * $this->_sFontSize},\n"
                    ."          {name:'created',index:'created', width:140 * "
                                ."$this->_sFontSize, sorttype:\"datetime\"},\n"
                    ."          {name:'block_button',index:'created', \"sortable\":false, "
                    // Insert the function to call the modal div
                                ."\"formatter\":insertModalLog, width:100 * $this->_sFontSize, sorttype:\"none\"}\n"
                    ."        ],\n"
                    // Define the table footer
                    ."        rowNum:" . $this->_iRowCount . ",\n"
                    ."        rowList:[10,20,30,50],\n"
                    ."        pager: '#" . self::PAGER_ID . "',\n"
                    ."        sortname: 'id',\n"
                    ."        viewrecords: true,\n"
                    ."        sortorder: \"DESC\",\n"
                    ."        height: \"100%\",\n"
                    ."        width: \"100%\",\n"
                    ."        caption: \"" . $this->_oTranslation->translate('caption_log_table') . "\",\n"
                    ."      });\n"
                    ."\n"
                    // Initialize the blocked data table
                    ."      jQuery(\"#" . self::TABLE_ID . "2\").jqGrid({\n"
                    ."        url:'" . $this->_sBaseURL
                                . "manager/index.php?a=112&id=$iModuleID&getdata="
                                . self::TYPE_GET_BLOCKED_DATA
                                . "',\n"
                    // Defining data source type
                    ."        datatype: \"json\",\n"
                    // Setting column captions
                    ."        colNames:[\n"
                    ."          '" . $this->_oTranslation->translate('caption_ID') . "',\n"
                    ."          '" . $this->_oTranslation->translate('caption_ip') . "',\n"
                    ."          '" . $this->_oTranslation->translate('caption_createdby') . "',\n"
                    ."          '" . $this->_oTranslation->translate('caption_created') . "',\n"
                    ."          '" . $this->_oTranslation->translate('caption_unblock_button') . "'\n"
                    ."        ],\n"
                    // Define the columns
                    ."        colModel:[\n"
                    ."          {name:'id',index:'id', width:60 * $this->_sFontSize, sorttype:\"int\"},\n"
                    ."          {name:'ip',index:'ip', width:140 * $this->_sFontSize},\n"
                    ."          {name:'createdby',index:'createdby', width:200 * $this->_sFontSize},\n"
                    ."          {name:'created',index:'created', width:170 * $this->_sFontSize},\n"
                    ."          {name:'block_button',index:'created', \"sortable\":false, "
                    // Insert the function to call the modal div
                              ."\"formatter\":insertModalBlock, width:120 * $this->_sFontSize, sorttype:\"none\"}\n"
                    ."        ],\n"
                    // Define the table footer
                    ."        rowNum:" . $this->_iRowCount . ",\n"
                    ."        rowList:[10,20,30,50],\n"
                    ."        pager: '#" . self::PAGER_ID . "2',\n"
                    ."        sortname: 'id',\n"
                    ."        viewrecords: true,\n"
                    ."        sortorder: \"DESC\",\n"
                    ."        height: \"100%\",\n"
                    ."        width: \"100%\",\n"
                    ."        caption: \"" . $this->_oTranslation->translate('caption_block_table') . "\",\n"
                    ."      });\n"
                    ."\n"
                    // Setting font sizes
                    ."      $('.ui-widget').css('font-size', '" . $this->_sFontSize . "em');\n"
                    ."      $('#dialog').css('font-size', '" . $this->_sFontSizeDialog . "em');\n"
                    ."    } );\n"
                    ."  </script>\n";

      // Get the complete HTML
      $result = $this->getHTML($sJavaScript, self::TABLE_ID, self::PAGER_ID, $iModuleID);

      return $result;

    } catch (Exception $e) {
      $this->logError($e);
    }
  } // getLogContent


  /**
   * Returns the log data from the database
   *
   * @param string $sTableName
   * @param integer $iModuleID
   * @return string
   */
  public function getLogData($sTableName, $iModuleID)
  {
    global $modx;

    try {
      $this->createLogTable($sTableName);

      $iModuleID = is_numeric($iModuleID) ? $iModuleID : 0;

      $oTableInfos = $this->getTableInfos($sTableName);
      
      $html = new HTML();

      $sSQL = 'SELECT SQL_CALC_FOUND_ROWS `id`, `name`, `value`, `page`, `ip`, `impact`, `origin`, `created` '
            .'FROM ' . $sTableName . ' '
            .'ORDER BY ' . $this->getSortIndex() . ' ' . $this->getSortOrder() . ' '
            .'LIMIT ' . $oTableInfos->iStart . ', ' . $oTableInfos->iLimit;

      $rRecordset =  $modx->db->query($sSQL);

      $result->page = $oTableInfos->iPage;
      $result->total = $oTableInfos->iTotalPages;
      $result->records = $oTableInfos->iRecordCount;

      $i = 0;
      while ($row = mysql_fetch_object($rRecordset)) {
        $ripeLink = $html->getLink(
                sprintf(self::RIPE, $row->ip), 
                '', 
                '', 
                sprintf($this->_oTranslation->translate('title_ripe'), $row->ip),
                true,
                $row->ip);
        
        $intrusionTestLink = $html->getLink(
                sprintf(self::PHPIDS_TEST_URI, urlencode($row->value)), 
                '', 
                '', 
                $this->_oTranslation->translate('title_test_on_phpids'), 
                true, 
                $row->impact);

        $infoSniperLink = $html->getLink(
                sprintf(self::INFOSNIPER, $row->ip), 
                '', 
                '', 
                sprintf($this->_oTranslation->translate('title_infosniper'), $row->ip), 
                true, 
                $row->origin);

        $result->rows[$i]['id'] = $row->id;
        $result->rows[$i]['cell'] =
          array(
            $row->id,
            $row->name,
            urlencode($row->value),
            $row->page,
            $ripeLink,
            $intrusionTestLink,
            $infoSniperLink,
            $row->created,
            $this->getBlockForm(
                    $row->id,
                    $row->ip,
                    $this->_sBaseURL
                      . 'manager/index.php?a=112&id='
                      . $iModuleID
                      . '&getdata='
                      . self::TYPE_BLOCK_IP,
                    $this->_oTranslation->translate('caption_block_button')
            )
          );
        $i++;
      }

      return json_encode($result);

    } catch (Exception $e) {
      $this->logError($e);
    }
  } // getLogData


  /**
   * Returns the data of blocked IP-addresses from the database
   *
   * @param string $sTableName
   * @param integer $iModuleID
   * @return string
   */
  public function getBlockedData($sTableName, $iModuleID)
  {
    global $modx;

    try {
      $this->createBlockTable($sTableName);
      
      $html = new HTML();

      $iModuleID = is_numeric($iModuleID) ? $iModuleID : 0;

      $oTableInfos = $this->getTableInfos($sTableName);

      $sSQL = 'SELECT SQL_CALC_FOUND_ROWS `id`, `ip`, `createdby`, `created` '
            .'FROM ' . $sTableName . ' '
            .'ORDER BY ' . $this->getSortIndex() . ' ' . $this->getSortOrder() . ' '
            .'LIMIT ' . $oTableInfos->iStart . ', ' . $oTableInfos->iLimit;

      $result->page = $oTableInfos->iPage;
      $result->total = $oTableInfos->iTotalPages;
      $result->records = $oTableInfos->iRecordCount;

      $rRecordset =  $modx->db->query($sSQL);

      $i = 0;
      while ($row = mysql_fetch_object($rRecordset)) {
        $ripeLink = $html->getLink(
                sprintf(self::RIPE, $row->ip), 
                '', 
                '', 
                sprintf($this->_oTranslation->translate('title_ripe'), $row->ip), 
                true, 
                $row->ip);

        $result->rows[$i]['id'] = $row->id;
        $result->rows[$i]['cell'] = array(
          $row->id,
          $ripeLink,
          $row->createdby,
          $row->created,
          $this->getBlockForm($row->id,
                  $row->ip,
                  $this->_sBaseURL
                    . 'manager/index.php?a=112&id='
                    . $iModuleID
                    . '&getdata='
                    . self::TYPE_UNBLOCK_IP,
                  $this->_oTranslation->translate('caption_unblock_button'))
        );
        $i++;
      }

      return json_encode($result);

    } catch (Exception $e) {
      $this->logError($e);
    }
  } // getBlockedData


  /**
   * Writes an IP-address to the database, that will be blocked by the plugin
   *
   * @param string $sIPAddress
   * @param string $sTableName
   * @return string
   */
  public function blockIP($sIPAddress, $sTableName)
  {
    global $modx;

    try {
      $this->createBlockTable($sTableName);
      $aUserInfo = $modx->getUserInfo($_SESSION['mgrInternalKey']);

      $sSQL =  'SELECT Count(*) iRecordCount '
              .'FROM ' . mysql_real_escape_string($sTableName) . ' '
              .'WHERE `ip` = \'' . mysql_real_escape_string($sIPAddress) . '\'';

      $rRecordset =  $modx->db->query($sSQL);

      while ($row = mysql_fetch_object($rRecordset)) {
        $iRecordCount = $row->iRecordCount;
      }

      if ($iRecordCount == 0) {
        $sSQL =  'INSERT INTO ' . mysql_real_escape_string($sTableName)
                . '(`ip`, `createdby`, `created`) '
                .'VALUES(\'' . mysql_real_escape_string($sIPAddress) 
                . '\', \'' . mysql_real_escape_string($aUserInfo['username'])
                . '\', CURRENT_TIMESTAMP)';
        $modx->db->query($sSQL);

        $result = str_replace('%s', htmlentities($sIPAddress),
                $this->_oTranslation->translate('message_ip_blocked'));
      } else {
        $result = str_replace('%s', htmlentities($sIPAddress),
                $this->_oTranslation->translate('message_ip already_blocked'));
      }

      return $result;

    } catch (Exception $e) {
      $this->logError($e);
    }
  } // blockIP

  /**
   * Removes an IP-address from the database, that will not be blocked further on by the plugin
   *
   * @param string $sIPAddress
   * @param string $sTableName
   * @return string
   */
  public function unBlockIP($sIPAddress, $sTableName)
  {
    global $modx;

    try {
      $this->createBlockTable($sTableName);

      $sSQL = 'DELETE FROM ' . mysql_real_escape_string($sTableName)
             .' WHERE `ip` = \'' . mysql_real_escape_string($sIPAddress) . '\'';
      $modx->db->query($sSQL);

      $result = str_replace('%s', htmlentities($sIPAddress),
              $this->_oTranslation->translate('message_ip_unblocked'));

      return $result;

    } catch (Exception $e) {
      $this->logError($e);
    }
  } // blockIP


  /**
   * Returns the details of a log record
   *
   * @param string $sTableName
   * @return string
   */
  public function getLogIDDetail($sTableName)
  {
    global $modx;

    try {
      $result->iIDLog = $this->getLogID();

      $sSQL = 'SELECT `id`, `name`, `value`, `page`, `ip`, `impact`, `origin`, `created` '
            .'FROM ' . $sTableName . ' '
            .'WHERE `id` = ' . $result->iIDLog;

      $rRecordset =  $modx->db->query($sSQL);

      while ($row = mysql_fetch_object($rRecordset)) {
        $result->name = htmlentities($row->name);
        $result->value = htmlentities($row->value);
        $result->page = htmlentities($row->page);
        $result->ip = htmlentities($row->ip);
        $result->impact = $row->impact;
        $result->origin = $row->origin;
        $result->created = $row->created;
      }

      return json_encode($result);

    } catch (Exception $e) {
      $this->logError($e);
    }
  } // getLogIDDetail


  /**
   * Returns the details of a log record
   *
   * @param string $sTableName
   * @return string
   */
  public function getBlockIDDetail($sTableName)
  {
    global $modx;

    try {

      $result->iIDLog = $this->getLogID();

      $sSQL = 'SELECT `id`, `ip`, `createdby`, `created` '
            .'FROM ' . mysql_real_escape_string($sTableName) . ' '
            .'WHERE `id` = ' . $result->iIDLog;

      $rRecordset =  $modx->db->query($sSQL);

      while ( $row = mysql_fetch_object($rRecordset)) {
        $result->ip = htmlentities($row->ip);
        $result->createdby = $row->createdby;
        $result->created = $row->created;
      }

      return json_encode($result);

    } catch (Exception $e) {
      $this->logError($e);
    }
  } // getBlockIDDetail


  /**
   * Truncate the log table
   *
   * @param string $sTableName
   * @return string A text to inform, that the table is empty now
   */
  public function emptyLogTable($sTableName)
  {
    global $modx;
    $result = false;

    try {

      $sSQL = 'TRUNCATE TABLE ' . $sTableName;

      $modx->db->query($sSQL);

      $result = $this->_oTranslation->translate('message_truncated_log');

    } catch (Exception $e) {
      $this->logError($e);
    }

    return $result;
  } // emptyLogTable


  /**
   * Delete a record of the log table
   *
   * @param string $sTableName
   * @return boolean whether the delete was done or an error occured
   */
  public function deleteLogRecord($sTableName)
  {
    global $modx;
    $result = false;

    try {

      $sSQL =  'DELETE FROM ' . $sTableName
             . 'WHERE `id` = ' . $this->getLogID();

      $modx->db->query($sSQL);

      $result = true;

    } catch (Exception $e) {
      $this->logError($e);
    }

    return $result;
  } // deleteLogRecord

  /**
   * Calls the RSS feed of PHPIDS filter updates and checks, whether there is
   * a new filter available, or not. The result is the HTML form if an update
   * is availabe, otherwise the result is a note that there are no updates
   * available.
   *
   * @return string A div with the id FilterUpdate and a filter update button,
   *                if updates are available
   */
  private function getFilterButton()
  {
    try {
      $rss = new RSSFeed();
      $rss->setMaxEntries(1);
      
      $html = new HTML();

      // Open the div element for filter data
      $result = "<div id=\"FilterUpdate\">\n";
      
      $autoUpdate = new phpidsAutoupdate($this->_phpidsLibPath, $this->_translationClassName);
      
      /*
      if ($rss->createEntryList(urlencode(self::PHPIDS_FILTER_RSS_URI))) {

        $feed = $rss->current();
        $timeStamp = $feed->getTimeStamp();

        $options = new Options($this->_tableNames[self::TABLE_NAME_OPTIONS]);
        $lastTimeStamp = $options->getOption(Options::OPTION_LAST_FILTER_UPDATE);

        if ($timeStamp > $lastTimeStamp) {
          $caption = sprintf(
                  $this->_oTranslation->translate('caption_update_filter'),
                  date(
                          $this->_oTranslation->translate('date_time_format'),
                          $timeStamp
                          )
                  );

          // Set the file name
          $fileName = $this->_phpidsLibPath . '/IDS/default_filter.xml';

          // Checks, whether curl is available, if not, the update button is not available
          if (ini_get('allow_url_fopen') && is_writable($fileName)) {
            // Create update button
            $button = new HTMLButtons();
            $button->setName('UpdateFilter');
            $button->setCaption($this->_oTranslation->translate('button_update_filter'));
            $button->setType(HTMLButtons::TYPE_BUTTON);
            $button->setOnClick('updateFilter();');
            $buttonText = $button->getButton();
          } else {
            $buttonText = '';
          }

          // Create the Button to make updates available
          $result .= "<form id=\"updatefilter\" name=\"upatefilter\">\n"
                    ."<p>\n"
                    .$caption . ' ' . $buttonText
                    ."</p>\n"
                    ."<p>\n"
                    .$html->getLink(
                            $feed->getLink(), 
                            '', 
                            '', 
                            '', 
                            true, 
                            $this->_oTranslation->translate('caption_link_phpids_trunk'))
                    ."</p>\n"
                    ."</form>\n";
        } else {        
          // Create message, that no updates are available
          $result .= '<p>'
                    .$this->_oTranslation->translate('caption_no_filter_updates')
                    ."</p>\n";
        }
      } else {
        $result .= '<p>'
                  .$this->_oTranslation->translate('caption_filter_uri_error')
                  ."</p>\n";
      }
       * 
       */

      $result .= "</div>\n";
    } catch (Exception $e) {
      $this->logError($e);
    }

    return $result;
  } // getFilterButton

  /**
   * Updates the local filter with new filter from www.phpids.org and returns a
   * new text for the FilterUpdate div.
   *
   * @return string If the update was done without errors, a new text for the
   *                FilterUpdate div is returned
   */
  public function updateDefaultFilter()
  {
    $result = '';

    try {
      // Create the feed object
      $rss = new RSSFeed();
      $rss->setMaxEntries(1);
      if ($rss->createEntryList(urlencode(self::PHPIDS_FILTER_RSS_URI))) {

        $feed = $rss->current();
        $timeStamp = $feed->getTimeStamp();

        // Set the file name
        $fileName = $this->_phpidsLibPath . '/IDS/default_filter.xml';

        // Open the local file with rewriting the content
        $handle = fopen($fileName, 'w+');

        if ($handle) {

          if (ini_get('allow_url_fopen')) {
            // Open external file
            $updateHandle = fopen(urlencode(self::PHPIDS_DEFAULT_FILTER_URI), 'r');

            if ($updateHandle) {
              // Write the new filter
              while (($line = fgets($updateHandle)) !== false) {
                fwrite($handle, $line);
              }

              // Write the filter date to the options
              $options = new Options($this->_tableNames[self::TABLE_NAME_OPTIONS]);
              $options->setOption(Options::OPTION_LAST_FILTER_UPDATE, $timeStamp);

              // New text for the updatefilter div
              $result = '<p>'
                       .$this->_oTranslation->translate('caption_no_filter_updates')
                       ."</p>\n";
            }
            // Close external file
            fclose($updateHandle);
          }
          // Close local file
          fclose($handle);
        }
      } else {
        $result .= '<p>'
                  .$this->_oTranslation->translate('caption_filter_uri_error')
                  ."</p>\n";
      }
    } catch (Exception $e) {
      $this->logError($e);
    }

    return $result;
  } // updateDefaultFilter

  /**
   * Returns the TruncateTable div with a button, to start truncating the table.
   *
   * @return string The TruncateTable div
   */
  private function getTruncateTable()
  {
    $result = '';

    try {
      $button = new HTMLButtons();
      $button->setName('EmptyTable');
      $button->setCaption($this->_oTranslation->translate('caption_truncate_log'));
      $button->setType(HTMLButtons::TYPE_BUTTON);
      $button->setOnClick('emptyLogTable();');
      $buttonText = $button->getButton();

      $result = "<div id=\"TruncateTable\">\n"
               ."<p>\n"
               .$this->_oTranslation->translate('message_truncate_log') . ' ' . $buttonText
               ."</p>\n"
               ."</div>\n";
    } catch (Exception $e) {
      $this->logError($e);
    }

    return $result;
  } // getTruncateTable

  /**
   * Returns the download div for downloading the intrusion table as CSV file.
   *
   * @param int $iModuleID The module ID of MODx for PHPIDS
   * @return string The div element with the download link
   */
  private function getDownloadLink($iModuleID)
  {
    $result = '';

    try {
      // Create the link
      $html = new HTML();
      $link = $html->getLink($this->_sBaseURL . 'manager/index.php?a=112&id=' 
                  . $iModuleID .'&getdata=' . self::TYPE_GET_CSV,
              '', 
              '', 
              '', 
              false, 
              $this->_oTranslation->translate('caption_download_csv'));

      // Build the download div element
      $result = "<div id=\"DownloadCSV\">\n"
              ."<p>\n"
              .$link
              ."</p>\n"
              ."</div>\n";
    } catch (Exception $e) {
      $this->logError($e);
    }

    return $result;
  } // getDownloadLink

  private function getNewsList()
  {
    $result = '';

    try {
      // Create the feed object
      $rss = new RSSFeed();
      $rss->setMaxEntries(3);
      
      $html = new HTML();
      
      if ($rss->createEntryList(urlencode(self::NEWS_RSS_URI))) {
        $result = "<ul>\n";

        foreach ($rss as $rssFeed => $feed) {
          $link = $html->getLink(
                  $feed->getLink(), 
                  '', 
                  '', 
                  $feed->getTitle(), 
                  false, 
                  $feed->getTitle()
                  );

          $result .= "  <li>\n"
                    ."    <p>\n"
                    .'     ' . $link
                    .$html->getStrong(
                       date(
                            $this->_oTranslation->translate('date_time_format'),
                            $timeStamp
                       )
                    )
                    ."    </p>\n"
                    ."    <div>\n"
                    .$feed->getText()
                    ."    </div>\n"
                    .'    <p>'
                    .sprintf($this->_oTranslation->translate('caption_link_more'), $feed->getLink())
                    ."</p>\n"
                    ."  </li>\n";
        }

        $result .= "</ul>\n";
      }
    } catch (Exception $e) {
      $this->logError($e);
    }

    return $result;
  } // getNewsList

  /**
   * Sets the delimiter for the CSV export
   *
   * @param string $value The delimiter for the CSV export
   */
  public function setCsvDelimiter($value)
  {
    $this->_csvDelimiter = $value;
  } // setCsvDelimiter

  /**
   * Sets the enclosure for the CSV export
   *
   * @param string $value The enclosure for the CSV export
   */
  public function setCsvEnclosure($value)
  {
    $this->_csvEnclosure = $value;
  } // setCsvEnclosure

  /**
   * Returns the current intrusions as CSV file.
   *
   * @param string $sTableName
   */
  public function exportImntrusionsAsCSV($sTableName)
  {
    global $modx;

    // Open the handle
    $output = fopen('php://temp/maxmemory:'. (5*1024*1024), 'r+');

    // Add a header row with captions in the current language
    $columns = array(
      $this->_oTranslation->translate('caption_ID'),
      $this->_oTranslation->translate('caption_name'),
      $this->_oTranslation->translate('caption_value'),
      $this->_oTranslation->translate('caption_page'),
      $this->_oTranslation->translate('caption_ip'),
      $this->_oTranslation->translate('caption_impact'),
      $this->_oTranslation->translate('caption_origin'),
      $this->_oTranslation->translate('caption_created'));

    // Write the header row
    fputcsv($output, $columns, $this->_csvDelimiter, $this->_csvEnclosure);

    $sSQL = 'SELECT SQL_CALC_FOUND_ROWS `id`, `name`, `value`, `page`, `ip`, `impact`, `origin`, `created` '
           .'FROM ' . $sTableName
           .' ORDER BY `id`';

    // Run the query
    $rRecordset =  $modx->db->query($sSQL);
    while ($row = mysql_fetch_object($rRecordset)) {
      // Write the CSV row
      fputcsv($output,
              array(
                $row->id,
                $row->name,
                $row->value,
                $row->value,
                $row->page,
                $row->ip,
                $row->impact,
                $row->origin,
                $row->created
              ),
              $this->_csvDelimiter,
              $this->_csvEnclosure
      );
    }

    // Set the handle to start of the stream
    rewind($output);

    // Get the stream content for the file
    $csv = stream_get_contents($output);

    // Close the handle
    fclose($output);

    header('Content-type: application/octet-stream');

    // Set the filename
    $filename = 'intrusions.' . date('Y-m-d') . '.csv';
    header("Content-Disposition: attachment; filename=\"$filename\"");

    // Return the result
    return $csv;
  } // exportIntrusionsAsCSV
} // modulPHPIDS
