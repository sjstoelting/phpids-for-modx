//<?php
/**  
 * @name PHPIDS
 *
 * @author Stefanie Janine Stoelting, mail@stefanie-stoelting.de
 * @modification Thomas Jakobi, thomas.jakobi@partout.info
 * @changes Use MODx logging/Use MODx email settings/use MODx Plugin configuration (don't need to patch config.ini and/or plugin code)
 * @link http://code.google.com/p/phpids-for-modx/ Project home page
 * @link http://phpids.org/
 * @package PHPIDS
 * @license LGPL
 * @since 2012/03/31
 * @version 0.7.1.5
 * 
 * <strong>0.7.1.5</strong> A plugin to include PHPIDS into MODx to log and prevent intrusions
 *
 * If you install PHPIDS into a public path, you should remove webaccess to the
 * lib path, for example with the attached .htaccess file.
 *
 * This plugin is based on an article published by Heise Verlag:
 * Getting started with the PHPIDS intrusion detection system
 * http://www.h-online.com/security/Getting-started-with-the-PHPIDS-intrusion-detection-system--/features/113163
 *
 * Requirements: MODx 1.0.5 or newer, PHP 5.1.6 or newer, PHPIDS 0.7
 *
 * On page Systemevents check the entry OnWebPageInit
 *
 * For a test just call your site with the following parameters: index.php?id=1&test=">XXX
 **/
$bUseMODxBasePath           = (isset($useMODxBasePath) && (strtolower($useMODxBasePath) != 'true') && ($useMODxBasePath != '1')) ? false : true;
$bUseLogFile                = (isset($useLogFile) && (strtolower($useLogFile) != 'true') && ($useLogFile != '1')) ? false : true;
$bUseEmail                  = (isset($useEmail) && (strtolower($useEmail) != 'true') && ($useEmail != '1')) ? false : true;
$sMailFrom                  = (isset($mailFrom) && (!empty($mailFrom))) ? $mailFrom : $modx->config['emailsender'];
$bUseDatabase               = (isset($useDatabase) && (strtolower($useDatabase) != 'true') && ($useDatabase != '1')) ? false : true;
$bUseMODxLog                = (isset($useMODxLog) && (strtolower($useMODxLog) != 'true') && ($useMODxLog != '1')) ? false : true;
$bUseMODxTablePrefix        = (isset($useMODxTablePrefix) && (strtolower($useMODxTablePrefix) != 'true') && ($useMODxTablePrefix != '1')) ? false : true;
$iLogFromImpact             = (isset($logFromImpact) && is_numeric($logFromImpact)) ? $logFromImpact : 5;
$iSendMailFromImpact        = (isset($sendMailFromImpact) && is_numeric($sendMailFromImpact)) ? $sendMailFromImpact : 25;
$sIdsRecipients             = isset($idsRecipients) ? $idsRecipients: $modx->config['emailsender'];
$sIdsSubject                = isset($idsSubject) ? $idsSubject: $modx->config['site_name'] . ': PHPIDS Plugin detected an intrusion attempt!';
$sSMTPServer                = isset($SMTPServer) ? $SMTPServer : '';
$iSMTPPort                  = isset($SMTPPort) ? $SMTPPort : '25';
$sSMTPAccount               = isset($SMTPAccount) ? $SMTPAccount : '';
$sSMTPPassword              = isset($SMTPPassword) ? $SMTPPassword : '';
$iBlockedDocumentID         = (isset($blockedDocumentID) && is_numeric($blockedDocumentID)) ? $blockedDocumentID: $modx->config['site_start'];
$iRedirectIntrusionLevel    = (isset($redirectIntrusionLevel) && is_numeric($redirectIntrusionLevel)) ? $redirectIntrusionLevel: 50;
$iRedirectIntrusionID       = (isset($redirectIntrusionID) && is_numeric($redirectIntrusionID)) ? $redirectIntrusionID: 0;
$bUseSMTP                   = !empty($sSMTPServer ) && !empty($iSMTPPort) && !empty($sSMTPAccount) && !empty($sSMTPPassword);
$useExceptions              = isset($useExceptions) ? explode('|', $useExceptions) : array();

if ($bUseMODxBasePath) {

  $sBasePath           = MODX_BASE_PATH . 'assets/lib/phpids/lib';

} else {

  $sBasePath           = isset($basePath) ? $basePath : '';

}

if ( empty($sBasePath) ) {

  $modx->logEvent(0, 2, 'PHPIDS Plugin: PHPIDS path not set!');

  // Exeption is only shown, on backend login
  if (isset($_SESSION['mgrValidated']) ? $_SESSION['mgrValidated']: false == 1) {
    $modx->messageQuit('PHPIDS Plugin: PHPIDS path not set!');
  }
  
} else {

  try {
  set_include_path(get_include_path()
     . PATH_SEPARATOR
     . $sBasePath);

  require_once('IDS/Init.php');

    $aRequest = array(
      'REQUEST' => $_REQUEST,
      'GET' => $_GET,
      'POST' => $_POST,
      'COOKIE' => $_COOKIE
    );

    if (file_exists($sBasePath . '/IDS/Config/Config.ini.php')) {

      $sIniFile = $sBasePath . '/IDS/Config/Config.ini.php';

    } else {

      // Exeption is only shown, on backend login
      if (isset($_SESSION['mgrValidated']) ? $_SESSION['mgrValidated'] : false == 1) {

        $modx->messageQuit(sprintf('PHPIDS configuration file %s not found!', $sBasePath . '/IDS/Config/Config.ini.php'));

      } else {

        $modx->logEvent(0, 2, sprintf('PHPIDS configuration file %s not found!', $sBasePath . '/IDS/Config/Config.ini.php'), 'PHPIDS');

        // Break here, because PHPIDS can't work without the ini file end ends with an error
        return;

      }
    }

    $init = IDS_Init::init($sIniFile);

    $init->config['General']['base_path'] = $sBasePath . '/IDS/';
    $init->config['General']['use_base_path'] = true;
    $init->config['General']['exceptions'] = array_merge($init->config['General']['exceptions'], $useExceptions);
    
    // E-Mail configuration
    $init->config['Logging']['recipients'] = $sIdsRecipients;
    $init->config['Logging']['subject'] = $sIdsSubject;
    $init->config['Logging']['header'] = 'From: ' . $modx->config['emailsender'];
    $init->config['Caching']['caching'] = 'none';

    // Database configuration
    $init->config['Logging']['wrapper'] = 'mysql:host=' . $modx->db->config['host'] . ';port=3306;dbname=' . trim($modx->db->config['dbase'], '`');
    $init->config['Logging']['user'] = $modx->db->config['user'];
    $init->config['Logging']['password'] = $modx->db->config['pass'];
    

    $ids      = new IDS_Monitor($aRequest, $init);
    $result   = $ids->run();
    $iImpact  = $result->getImpact();

    // Include the module class
    require_once(MODX_BASE_PATH . 'assets/modules/phpids/classes/class.module.phpids.php');

    if ($bUseMODxTablePrefix) {
      $tablePrefix = $modx->db->config['table_prefix'];
    } else {
      $tablePrefix = '';
    }
    $sTableIntrusions =  $tablePrefix . modulePHPIDS::TABLE_NAME_INTRUSIONS;
    $sTableBlock =  $tablePrefix . modulePHPIDS::TABLE_NAME_BLOCK;

    $init->config['Logging']['table'] = $sTableIntrusions;

    if (!$result->isEmpty()) {

      require_once('IDS/Log/Composite.php');

      $compositeLog = new IDS_Log_Composite();

      if ($bUseLogFile && $iImpact >= $iLogFromImpact) {

        require_once('IDS/Log/File.php');
        $compositeLog->addLogger(IDS_Log_File::getInstance($init));

      }


      if ($bUseEmail && $iImpact >= $iSendMailFromImpact) {

        if ($bUseSMTP) {

          require_once($modx->config['base_path']. 'manager/includes/controls/class.phpmailer.php');
          $cPHPMailer = new PHPMailer();
          
          try {
            // Configure SMTP
            $cPHPMailer->IsSMTP();
            $cPHPMailer->SMTPAuth = true; //Turn on SMTP authentication
            $cPHPMailer->Host = $sSMTPServer;
            $cPHPMailer->Port = $iSMTPPort;
            $cPHPMailer->Username = $sSMTPAccount;
            $cPHPMailer->Password = $sSMTPPassword;
            $cPHPMailer->CharSet = 'UTF-8';
            
            // Add recipients
            $aRecipients = explode(',', $sIdsRecipients);
            foreach ($aRecipients as $sRecipient) {
              $cPHPMailer->AddAddress($sRecipient);
            }
            $cPHPMailer->FromName = $sMailFrom;
            $cPHPMailer->From =  $modx->config['emailsender'];
            
            $cPHPMailer->Subject = $sIdsSubject;
            
            $format = "The following attack has been detected by PHPIDS\n\n"
                     ."IP: %s \n\n"
                     ."Date: %s \n\n";

            $mailBody = sprintf($format,
                          $_SERVER['REMOTE_ADDR'],
                          date('c'));
            
            $mailBody .= str_replace('<br/>', "\n", $result);
            
            $cPHPMailer->Body = $mailBody;
            
            $cPHPMailer->Send();
            
          } catch (Exception $e) {
            
            $modx->logEvent(0, 3, sprintf('An error occured while creating SMTP mail: %s', $e->getMessage()), 'PHPIDS Plugin');
            
          }

          

        } else {
          
          require_once('IDS/Log/Email.php');
          $compositeLog->addLogger(IDS_Log_Email::getInstance($init));

        }

      }

      if ($bUseDatabase && $iImpact >= $iLogFromImpact) {

        $sPHPIDClassFile = MODX_BASE_PATH . 'assets/modules/phpids/classes/class.module.phpids.php';

        if (file_exists($sPHPIDClassFile)) {
          $oPHPIDS = new modulePHPIDS();

          $oPHPIDS->createLogTable($sTableIntrusions);
          $oPHPIDS->createBlockTable($sTableBlock);

        } else {

          // Exeption is only shown, on backend login
          if (isset($_SESSION['mgrValidated']) ? $_SESSION['mgrValidated'] : false == 1) {

            $modx->messageQuit(sprintf('PHPIDS class file %s not found!', $sPHPIDClassFile));

          } else {

            $modx->logEvent(0, 2, sprintf('PHPIDS class file %s not found!', $sPHPIDClassFile), 'PHPIDS');

            // Break here, because PHPIDS can't work without the ini file end ends with an error
            return;

          }

        }

        require_once('IDS/Log/Database.php');
        $compositeLog->addLogger(IDS_Log_Database::getInstance($init));

      }

      if ($bUseMODxLog && $iImpact >= $iLogFromImpact) {

        $modx->logEvent(0, 2, sprintf('IP: %s<br /><br />', $_SERVER['REMOTE_ADDR']) . $result, sprintf('PHPIDS Intrusion Alert! Impact: %s', $iImpact), 'PHPIDS Plugin');
          
      }

      $compositeLog->execute($result);
    }

    /** Redirect intrusions if the level is higher or equal the definition and the document id is higher 0 **/
    if ($iImpact >= $iRedirectIntrusionLevel && $iRedirectIntrusionID && is_numeric($iRedirectIntrusionID)) {

      $modx->sendForward($iRedirectIntrusionID);

    }

    /** Block users **/
    if ($bUseDatabase) {
      $sSQL = 'SELECT Count(*) iIPDetected '
             .'FROM ' . $sTableBlock . ' '
             .'WHERE \'' . $_SERVER['REMOTE_ADDR'] . '\' LIKE Concat( `ip` , \'%\' ) ';
      $rRecordset =  $modx->db->query($sSQL);

      $bBlockCall = false;
      
      while ($row = mysql_fetch_object($rRecordset)) {
        $bBlockCall = $row->iIPDetected != 0;

        // If the call has to be blocked, send redirect
        if ($bBlockCall) {
          
          if (is_numeric($iBlockedDocumentID)) {

            $modx->sendForward($iBlockedDocumentID);

          } else {

            $modx->sendUnauthorizedPage();
          }
        }
      }

    }

  } catch (Exception $e) {

    $modx->logEvent(0, 2, sprintf('An error occured in line %1$d: %2$s', $e->getLine(), $e->getMessage()), 'PHPIDS Plugin');

    // Exeption is only shown, on backend login
    if (isset( $_SESSION['mgrValidated']) ? $_SESSION['mgrValidated'] : false == 1) {

      $modx->messageQuit(sprintf('An error occured in file %1$s in line %2$d: %3$s code: %4s', $e->getFile(), $e->getLine(), $e->getMessage(), $e->getCode()));

    }

  }
}
