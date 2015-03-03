<?php
/**
 * CustomRequest
 *
 * Copyright 2013 by Thomas Jakobi <thomas.jakobi@partout.info>
 *
 * @package customrequest
 * @subpackage lexicon
 *
 * Default German Lexicon Entries for CustomRequest
 */

$_lang['customrequest'] = 'CustomRequest';
$_lang['customrequest_desc'] = 'Benutzerfreundliche URLs überall';

$_lang['customrequest.menu_home'] = 'CustomRequest';
$_lang['customrequest.menu_home_desc'] = 'Benutzerfreundliche URLs überall';

$_lang['setting_customrequest.debug'] = 'Debug-Informationen';
$_lang['setting_customrequest.debug_desc'] = 'Protokolliere Debug-Informationen im MODX Fehlerprotokoll';

$_lang['customrequest.configs'] = 'Konfigurationen';
$_lang['customrequest.configs_desc'] = 'Erstellen bzw. Bearbeiten Sie Ihre CustomRequest Konfigurationen.';
$_lang['customrequest.configs_desc_extended'] = "Die Konfigurationen werden in der Reihenfolge ausgeführt, in der sie in der Tabelle aufgeführt sind. Falls zwei Konfigurationen mit dem gleichen Alias-Pfad beginnen, dann wird die erste Konfiguration benutzt. Sie können Reihenfolge der Konfigurationen per Drag&amp;Drop ändern.";
$_lang['customrequest.configs_create'] = 'Neue Konfiguration';
$_lang['customrequest.configs_update'] = 'Konfiguration Bearbeiten';
$_lang['customrequest.configs_remove'] = 'Konfiguration Löschen';
$_lang['customrequest.configs_remove_confirm'] = 'Sind Sie sicher, dass sie diese CustomRequest Konfiguration löschen wollen?';
$_lang['customrequest.configs_name'] = 'Konfigurations-Name';
$_lang['customrequest.configs_alias'] = 'Alias-Pfad';
$_lang['customrequest.configs_alias_generated'] = 'Generiert';
$_lang['customrequest.configs_alias_desc'] = 'Mit diesem Pfad wird der Anfang einer nicht gefundenen URI verglichen. Wenn beide Pfade übereinstimmen, dann wird diese Konfiguration benutzt. Wenn der Alias-Pfad nicht gesetzt ist, wird der Alias-Pfad der angegegeben Ressource herangezogen.';
$_lang['customrequest.configs_resourceid'] = 'Ressource';
$_lang['customrequest.configs_resourceid_desc'] = 'Auf diese Ressource wird eine nicht gefundene URI bei erfolgreichem Vergleich mit dem Alias-Pfad umgeleitet.';
$_lang['customrequest.configs_urlparams'] = 'Request-Parameter';
$_lang['customrequest.configs_urlparams_desc'] = 'JSON kodiertes Array mit Request-/Get-/Post-Parameter Schlüsseln. Jedem Schlüssel wird ein Abschnitt des zweiten Teils (nach dem Alias-Pfad) der nicht gefundenen URI zugewiesen. Wenn kein regulärer Ausdruck angegeben ist, wird der zweite Teil am URI Trennzeichen \'/\' aufgeteilt';
$_lang['customrequest.configs_regex'] = 'Regulärer Ausdruck';
$_lang['customrequest.configs_regex_desc'] = 'Dieser optionale reguläre Ausdruck wird benurtzt, um den zweiten Teils der nicht gefundenen URI aufzuteilen. Die Fundstücke werden in der gefundenen Reihenfolge den einzelnen Request-Parametern zugewiesen';

$_lang['customrequest.error_msg'] = 'Fehler: [[+msg]]';

$_lang['customrequest.configs_err_invalid'] = 'Ungültige Konfiguration.';
$_lang['customrequest.configs_err_nf'] = 'Konfiguration nicht gefunden.';
$_lang['customrequest.configs_err_ns'] = 'Konfiguration nicht angegeben.';
$_lang['customrequest.configs_err_save'] = 'Beim Speichern der Konfiguration ist ein Fehler aufgetreten.';
$_lang['customrequest.configs_err_ns_alias_resourceid'] = 'Bitte einen Alias angegeben oder eine Ressource auswählen.';
