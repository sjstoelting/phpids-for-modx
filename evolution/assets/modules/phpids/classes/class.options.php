<?php
/* 
 * Options for the PHPIDS implementation
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
class Options {
  /**
   * Constant string with the option name for last filter update
   */
  const OPTION_LAST_FILTER_UPDATE = 'LAST_FILTER_UPDATE';

  /**
   * @var string The name of the table
   */
  private $_tableName;

  /**
   * @var boolean whether the table was checked for existance, or not
   */
  private $_tableExists = false;


  /**
   * Creates the option object
   *
   * @param string $tableName The name of the table used to store the options
   */
  public function  __construct($tableName)
  {
    if (!empty($tableName)) {
      $this->_tableName = mysql_real_escape_string($tableName);

      $this->checkTable();
    } else {
      throw new Exception('Table Name can\'t be empty.');
    }
  } // __construct

  /**
   * Checks, whether the option table exist and creates the table if it not
   * exist.
   */
  private function checkTable()
  {
    global $modx;

    $sSQL = 'CREATE TABLE IF NOT EXISTS `' . $this->_tableName . '` (
             `option_name` varchar(50) character set utf8 collate utf8_bin NOT NULL,
             `option_value` varchar(255) character set utf8 collate utf8_bin NOT NULL,
             `changed_by` varchar(100) character set utf8 collate utf8_bin NOT NULL,
             `changed` datetime NOT NULL,
              PRIMARY KEY (`option_name`)
             ) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin ;';

    $modx->db->query($sSQL);

    $this->_tableExists = true;
  } // checkTable

  /**
   * Write an option value with the given option name to the database.
   *
   * @param string $name The name of the option
   * @param string $value The value of the option
   */
  public function setOption($name, $value)
  {
    global $modx;

    if (!empty($name)) {
      // First delete the option
      $sSQL = 'DELETE FROM ' . $this->_tableName
            . ' WHERE `option_name` = \'' . mysql_real_escape_string($name) . '\'';
      $modx->db->query($sSQL);

      // Get the MODx user object
      $aUserInfo = $modx->getUserInfo($_SESSION['mgrInternalKey']);

      // Now insert the new option
      $sSQL = 'INSERT INTO ' . $this->_tableName . '
               (`option_name`, `option_value`, `changed_by`, `changed`)
               VALUES(\'' . mysql_real_escape_string($name) . '\',
               \'' . mysql_real_escape_string($value) .  '\',
               \'' . mysql_real_escape_string($aUserInfo['username']) . '\',
               CURRENT_TIMESTAMP)';

      $modx->db->query($sSQL);
    } else {
      throw new Exception('Option name can\' be empty.');
    }
  } // setOption

  /**
   * Returns the option value for the given name, empty if the option does not
   * exist.
   *
   * @param string $name The name of the option
   * @return string The option value for the given name, empty if the option does not exist
   * @throws If $name is empty
   */
  public function getOption($name)
  {
    global $modx;
    $result = '';

    if (!empty($name)) {
      $sSQL = 'SELECT `option_value`
               FROM ' . $this->_tableName . '
               WHERE `option_name` = \'' . mysql_real_escape_string($name) . '\';';

      $recordset = $modx->db->query($sSQL);
      if($row = mysql_fetch_object($recordset)) {
        $result = $row->option_value;
      }
    } else {
      throw new Exception('Option name can\'t be empty.');
    }

    return $result;
  } // getOption
} // Options