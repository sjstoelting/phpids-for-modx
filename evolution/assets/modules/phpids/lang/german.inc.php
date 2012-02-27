<?php
/* 
 * Contains german translation
 * @name PHPIDS
 *
 * @author Stefanie Janine Stoelting, mail@stefanie-stoelting.de
 * @link http://code.google.com/p/phpids-for-modx/ Project home page
 * @link http://phpids.org/
 * @package PHPIDS
 * @license LGPL
 * @since 2012/02/27
 * @version 0.7.1.4
 */
class TranslationGerman
{

  /**
   * Array with all translation in english
   *
   * @var array
   */
  private $_aTranslation = array(
    'h1_Error detected'               => 'Fehler aufgetreten',
    'txt_loading'                     => 'Lade Daten vom Server',
    'caption_log_table'               => 'PHPIDS Log Daten',
    'caption_block_table'             => 'PHPIDS Block Daten',
    'caption_ID'                      => 'ID',
    'caption_name'                    => 'Name',
    'caption_value'                   => 'Wert',
    'caption_page'                    => 'Seite',
    'caption_ip'                      => 'IP Adresse',
    'caption_impact'                  => 'Impact',
    'caption_origin'                  => 'Quelle',
    'caption_created'                 => 'Angelegt',
    'caption_createdby'               => 'Angelegt von',
    'caption_block_button'            => 'IP blocken',
    'caption_unblock_button'          => 'IP nicht blocken',
    'message_ip_blocked'              => 'Die IP Adresse %s ist von nun an geblockt.',
    'message_ip already_blocked'      => 'Die IP Adresse %s wurde bereits geblocked.',
    'message_ip_unblocked'            => 'Die IP Adresse %s wird nun nicht mehr geblockt.',
    'title_confirmation'              => 'Bestätigung benötigt',
    'button_close'                    => 'Schließen',
    'button_block_ip'                 => 'IP blockieren?',
    'button_unblock_ip'               => 'IP nicht blockieren?',
    'caption_tab_log_data'            => 'Log Daten',
    'caption_tab_blocked_data'        => 'Blocked Daten',
    'caption_tab_extended'            => 'Erweiterte Optionen',
    'caption_error_log'               => 'PHPIDS Modul',
    'text_error_log'                  => 'Ein Fehler trat in der Datei %1$s auf in Zeile %2$d: %3$s',
    'caption_truncate_log'            => 'Log Tabelle leeren',
    'message_truncate_log_confirm'    => 'Soll die Log Tabelle wirklich geleert werden?',
    'message_truncate_log'            => 'Alle Einträge in der Log Tabelle gehen dabei verloren.',
    'message_truncated_log'           => 'Die Log Tabelle ist nun leer.',
    'caption_truncate_log_progress'   => 'Leere Tabelle, bitte warten...',
    'button_update_filter'            => 'Update Filter',
    'caption_update_filter'           => 'Es ist ein Filter Update vom %s verf&uuml;gbar',
    'caption_no_filter_updates'       => 'Es ist kein Filter Update verfügbar.',
    'caption_update_filter_progress'  => 'Filter update läuft, bitte warten...',
    'caption_filter_uri_error'        => 'PHPIDS Filter URI ist nicht erreichbar.',
    'date_time_format'                => 'd.m.Y H:i',
    'caption_link_phpids_trunk'       => 'Mehr Informationen auf PHPIDS',
    'caption_news'                    => 'PHPIDS for MODx News',
    'caption_link_more'               => 'Read <a href="%s">more...</a>',
    'title_ripe'                      => 'Information &uuml;ber die IP Adresse %s in der RIPE Datenbank',
    'title_infosniper'                => 'Information &uuml;ber die IP Adresse %s auf Infosniper.net',
    'title_test_on_phpids'            => 'Smoke Test des Angriffes auf PHPIDS',
    'caption_download_csv'            => 'Download Intrusions als CSF Datei',
    'message_error_autoupdate'        => 'PHPIDS-Update: Ein Fehler ist aufgetreten: %s',
    'message_error_updatedownload'    => 'PHPIDS-Update: SHA1-hash des Downloads (%1$s) ist falsch! (Download nicht m&ouml;glich oder Man-in-the-Middle). SHA1 der Datei im trunk: %2$s, SHA1, provided by phpids.org: %3$s',
    'caption_update_filter'           => 'Filter:',
    'caption_update_state_ok'         => 'Kein Update verf&uuml;gbar.',
    'caption_update_state_not_ok'     => 'Update verf&uuml;gbar.',
    'caption_last_update_local'       => 'Letzte lokale &Auml;nderung: <strong>%s</strong>',
    'caption_last_update_phpids'      => 'Letzte &Auml;nderung auf phpids.org: <strong>%s</strong>',
    'caption_hash'                    => 'SHA-1 Hash: <br /> <code>%s</code>',
    'caption_local_remote'            => '(local)<br /> <code>%s</code>(remote)',
    'caption_no_update_available'     => 'Kein automatisches Update verf&uuml;gbar. (Dateien beschreibbar / Curl-Extension verf&uuml;gbar?)',
    'caption_run_update'              => 'Updates Starten',
    'caption_ids_version'             => 'IDS Version',
    'caption_download'                => 'Downloads',
    'caption_database'                => 'Datenbank',
    'caption_version'                 => 'Atkuelle plugin Version',
    'caption_loading_filter_update'   => 'Filter Update wird verarbeitet',
    'caption_filter_update_done'      => 'Das Filter Update erfolgreich beendet.',
    'caption_filter_update_error'     => 'Das Filter update wurde mit einem Fehler beendet, bitte &uuml;berpr&uuml;fen Sie das System Log.'
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