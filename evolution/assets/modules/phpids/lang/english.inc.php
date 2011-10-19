<?php
/* 
 * Contains english translation
 * @name PHPIDS
 *
 * @author Stefanie Janine Stoelting, mail@stefanie-stoelting.de
 * @link http://code.google.com/p/phpids-for-modx/ Project home page
 * @link http://phpids.org/
 * @package PHPIDS
 * @license LGPL
 * @since 2011/10/19
 * @version 0.7.alpha.1
 */
class TranslationEnglish
{
  /**
   * Array with all translation in english
   *
   * @var array
   */
  private $_aTranslation = array(
    'h1_Error detected'               => 'Error detected',
    'txt_loading'                     => 'Loading data from server',
    'caption_log_table'               => 'PHPIDS Log Data',
    'caption_block_table'             => 'PHPIDS Block Data',
    'caption_ID'                      => 'ID',
    'caption_name'                    => 'Name',
    'caption_value'                   => 'Value',
    'caption_page'                    => 'Page',
    'caption_ip'                      => 'IP Address',
    'caption_impact'                  => 'Impact',
    'caption_origin'                  => 'Origin',
    'caption_created'                 => 'Created',
    'caption_createdby'               => 'Created By',
    'caption_block_button'            => 'Block IP',
    'caption_unblock_button'          => 'Unblock IP',
    'message_ip_blocked'              => 'The IP address %s is blocked from now on.',
    'message_ip already_blocked'      => 'The IP address %s was already blocked.',
    'message_ip_unblocked'            => 'The IP address %s is not blocked from now on.',
    'title_confirmation'              => 'Confirmation Required',
    'button_close'                    => 'Close',
    'button_block_ip'                 => 'Block IP?',
    'button_unblock_ip'               => 'Unblock IP?',
    'button_empty_log_table'          => 'Empty log table?',
    'caption_tab_log_data'            => 'Log Data',
    'caption_tab_blocked_data'        => 'Blocked Data',
    'caption_tab_extended'            => 'Extended Options',
    'caption_error_log'               => 'PHPIDS Module',
    'text_error_log'                  => 'An error occured in file %1$s in line %2$d: %3$s',
    'caption_truncate_log'            => 'Empty log table',
    'message_truncate_log_confirm'    => 'Do you really want to empty the log table?',
    'message_truncate_log'            => 'All entries of the log are lost after the table is emtied.',
    'message_truncated_log'           => 'The log table is empty now.',
    'caption_truncate_log_progress'   => 'Empty table, please wait...',
    'button_update_filter'            => 'Update Filter',
    'caption_update_filter'           => 'There is a filter update from %s available',
    'caption_no_filter_updates'       => 'There is no filter update available.',
    'caption_update_filter_progress'  => 'Updating filter, please wait...',
    'caption_filter_uri_error'        => 'PHPIDS Filter URI is not available.',
    'date_time_format'                => 'Y-m-d H:i',
    'caption_link_phpids_trunk'       => 'More information at PHPIDS',
    'caption_news'                    => 'PHPIDS for MODx News',
    'caption_link_more'               => 'Read <a href="%s">more...</a>',
    'title_ripe'                      => 'Get information about the IP address %s from the RIPE database',
    'title_infosniper'                => 'Get information about the IP address %s from Infosniper.net',
    'title_test_on_phpids'            => 'Smoke test of the intrusion on PHPIDS',
    'caption_download_csv'            => 'Download intrusions as CSF file',
    'message_error_autoupdate'        => 'PHPIDS-Update: Some error occurred: %s',
    'message_error_updatedownload'    => 'PHPIDS-Update: SHA1-hash of the downloaded file ($s1) is incorrect! (Download failed or Man-in-the-Middle). SHA1 of the file in the trunk: %s2, SHA1, provided by phpids.org: %s3',
    'caption_update_filter'           => 'Filter:',
    'caption_update_state_ok'         => 'No update available.',
    'caption_update_state_not_ok'     => 'Update available.',
    'caption_last_update_local'       => 'Last local change on: <strong>%s</strong>',
    'caption_last_update_phpids'      => 'Last change on phpids.org: <strong>%s</strong>',
    'caption_hash'                    => 'SHA-1 Hash: <br /> <code>%s</code>',
    'caption_local_remote'            => '(local)<br /> <code>%s</code>(remote)',
    'caption_converter'               => 'Converter:',
    'caption_no_update_available'     => 'No automatic Update available. (files writable / Curl-Extension available?)',
    'caption_run_update'              => 'Start Updateds'
  );


  /**
   * Returns the translation of a given ID.
   *
   * @param string $sID
   * @return string
   */
  public function translate($sID) 
  {
    return $this->_aTranslation[$sID];
  } // translate
} // translation_en