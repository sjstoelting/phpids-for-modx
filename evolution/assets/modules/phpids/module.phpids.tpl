//<?php
/**
 * @name PHPIDS
 *
 * @author Stefanie Janine Stoelting, mail@stefanie-stoelting.de
 * @link http://code.google.com/p/phpids-for-modx/  Project home page
 * @link http://phpids.org/
 * @package PHPIDS
 * @license LGPL
 * @since 2012/02/18
 * @version 0.7.1.3
 * <strong>0.7.1.3</strong> A plugin to include PHPIDS into MODx to log and prevent intrusions
 *
 *
 * Module Configuration
 *
 * &useMODxBasePath=Use MODX base path?;list;true,false;true &basePath=PHPIDS path?;string;assets/lib/phpids/lib &useLogFile=Use log file?;list;true,false;false &useEmail=Use email?;list;true,false;true &useDatabase=Use database?;list;true,false;true &useMODxTablePrefix=Use MODX table prefix?;list;true,false;true &useMODxLog=Use MODX Log?;list;true,false;true &logFromImpact=Start logging from impact?;int;5 &sendMailFromImpact=Start sending mails from impact, high (around 15) or very high (around 25-50) impact?;int;15 &idsRecipients=Mail recipients (separated by comma);text; &idsSubject=Mail subject <br />(keep empty to use default);text; &SMTPServer=SMPT server;text; &SMTPPort=SMPT port <br />(default is 25, leave empty to use default);text; &SMTPAccount=SMPT account;text; &SMTPPassword=SMPT password;text; &mailFrom=E-Mail From Name<br> Only used with SMTP;text;PHPIDS;&useExceptions=Exceptions separated with pipes (|);text;&blockedDocumentID=ID of the MODx document, that blocked IPs would see, default is root;text&defaultTableEntries=Default table entries on start;text;20 &redirectIntrusionLevel=Redirect intrusions from a level (50 is very high);text;50 &redirectIntrusionID=Redirect intrusion to the document ID (0 means no redirection);text;0 &csvDelimiter=CSV delimiter (only one character);text;, &csvEnclosure=CSF enclosure (only one character);text;#
 *
 * Requirements: MODx 1.0.5 or newer, PHP 5.1.6 or newer, PHPIDS 0.7
 *
**/
$result = '';
$sOrder = '';
$sLimit = '';

// Set and check configuration values
$bUseDatabase = (isset($useDatabase) && (strtolower($useDatabase) != 'true' ) && ($useDatabase != '1')) ? false : true;
$bUseMODxTablePrefix = (isset($useMODxTablePrefix) && (strtolower($useMODxTablePrefix) != 'true') && ($useMODxTablePrefix != '1')) ? false : true;
$iModuleID = (isset($_GET['id']) && is_numeric($_GET['id'])) ? $_GET['id'] : -1;
$iDefaultTableEntries = (isset($defaultTableEntries) && is_numeric($defaultTableEntries)) ? $defaultTableEntries: 20;
$bUseMODxBasePath = (isset($useMODxBasePath) && (strtolower($useMODxBasePath) != 'true') && ($useMODxBasePath != '1')) ? false : true;
$csvDelimiter = isset($csvDelimiter) ? $csvDelimiter : ',';
$csvEnclosure = isset($csvEnclosure) ? $csvEnclosure : '#';

try {
  // Set the PHPIDS lib path
  if ($bUseMODxBasePath) {
    $sPhpidsBasePath = MODX_BASE_PATH . 'assets/lib/phpids/lib';
  } else {
    $sPhpidsBasePath = isset($basePath) ? $basePath : '';
  }

  // Manager language setting
  $sLanguage = $modx->config['manager_language'];

  // Checking individual user language setting, if not set, then the default language is used
  $sSQL =  'SELECT setting_name, setting_value AS sSettingValue '
          .'FROM ' . $modx->getFullTableName('user_settings') . ' '
          .'WHERE setting_name=\'manager_language\' '
          .'AND user=' . $modx->getLoginUserID();

  $rRecordset =  $modx->db->query($sSQL);

  while ($row = mysql_fetch_object($rRecordset) ) {
    $sLanguage = $row->sSettingValue;
  }


  // Include the module class
  require_once(MODX_BASE_PATH . 'assets/modules/phpids/classes/class.module.phpids.php');

  if ($iModuleID == -1) {

    $result = $oDocumentData->getErrorContent('Module ID not set, please change the configurarion. </ br> You\'ll find the module ID if you open the module in a new tab or window, it ist the field id in the address.');

  } else {
    // Which result to return
    $getData = (isset ($_GET['getdata']) && is_numeric($_GET['getdata'])) ? $_GET['getdata'] : 0;

    // Table Name
    if ($bUseDatabase) {
      if ($bUseMODxTablePrefix) {
        $tablePrefix = $modx->db->config['table_prefix'];
      } else {
        $tablePrefix = '';
      }

      $sTableIntrusions =  $tablePrefix . modulePHPIDS::TABLE_NAME_INTRUSIONS;
      $sTableBlock =  $tablePrefix . modulePHPIDS::TABLE_NAME_BLOCK;
      $sTableOptions = $tablePrefix . modulePHPIDS::TABLE_NAME_OPTIONS;

      $aTables = array(
        modulePHPIDS::TABLE_NAME_INTRUSIONS => $sTableIntrusions,
        modulePHPIDS::TABLE_NAME_BLOCK => $sTableBlock,
        modulePHPIDS::TABLE_NAME_OPTIONS => $sTableOptions)
      ;

      $oDocumentData = new modulePHPIDS(
              $sLanguage,
              0.8,
              $iDefaultTableEntries,
              0.8,
              $aTables
      );
      $oDocumentData->setPhpidsLibPath($sPhpidsBasePath);

      switch ($getData) {
        // Show log table form
        case modulePHPIDS::TYPE_GET_LOG_CONTENT:
          $oDocumentData->setCsvDelimiter($csvDelimiter);
          $oDocumentData->setCsvEnclosure($csvEnclosure);
          $result = $oDocumentData->getLogContent($iModuleID);

          break;

        // Get log data
        case modulePHPIDS::TYPE_GET_LOG_DATA: 
          $result = $oDocumentData->getLogData($sTableIntrusions, $iModuleID);

          break;

        // get blocked IP addresses
        case modulePHPIDS::TYPE_GET_BLOCKED_DATA: 
          $result = $oDocumentData->getBlockedData($sTableBlock, $iModuleID);

          break;

        // Block IP address
        case modulePHPIDS::TYPE_BLOCK_IP: 
          $sIPAddress = isset ($_GET['ipaddress'] )? mysql_real_escape_string($_GET['ipaddress']) : '';

          if (!empty($sIPAddress)) {

            $result = $oDocumentData->blockIP($sIPAddress, $sTableBlock);

          }

          break;

        // Unblock IP address
        case modulePHPIDS::TYPE_UNBLOCK_IP: 
          $sIPAddress = isset ($_GET['ipaddress']) ? mysql_real_escape_string($_GET['ipaddress']) : '';

          if (!empty($sIPAddress)) {

            $result = $oDocumentData->unBlockIP($sIPAddress, $sTableBlock);

          }

          break;

        // Get detail informations for blocking an IP address
        case modulePHPIDS::TYPE_GET_LOG_IDDETAIL: 
          $result = $oDocumentData->getLogIDDetail($sTableIntrusions);

          break;

        // Show unblock table form
        case modulePHPIDS::TYPE_GET_BLOCK_IDDETAIL: 
          $result = $oDocumentData->getBlockIDDetail($sTableBlock);

          break;

        // Truncate log table
        case modulePHPIDS::TYPE_TRUNCATE_LOG:
          $result = $oDocumentData->emptyLogTable($sTableIntrusions);

          break;

        // Delete log record
        case modulePHPIDS::TYPE_DELETE_LOG_RECORD:
          $result = $oDocumentData->deleteLogRecord($sTableIntrusions);

          break;

        // Update filter from phpids.org
        case modulePHPIDS::TYPE_UPDATE_FILTER:
          $result = $oDocumentData->updateDefaultFilter();

          break;

        // Create a CSV file from the intrusions table
        case modulePHPIDS::TYPE_GET_CSV:
          $result = $oDocumentData->exportIntrusionsAsCSV($sTableIntrusions);

          break;

        default:
          break;
      } // switch $getData
    } // $useDatabase
  }

  return $result;

} catch (Exception $e) {
  // All exceptions are logged

  if (!is_null($oDocumentData)) {

    // Log the error through th PHPIDS module class
    $oDocumentData->logError($e);

  } else {

    // The $oDocumentData (PHPIDS module class) was not created
    $modx->logEvent(0,
            2,
            sprintf('An error occured in line %1$d: %2$s',
                    $e->getLine(),
                    $e->getMessage()),
            'PHPIDS Module');
    
  }
}