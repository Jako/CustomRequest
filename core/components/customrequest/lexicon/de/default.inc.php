<?php
/**
 * Default Lexicon Entries for CustomRequest
 *
 * @package customrequest
 * @subpackage lexicon
 */
$_lang['customrequest'] = 'CustomRequest';
$_lang['customrequest.configs'] = 'Konfigurationen';
$_lang['customrequest.configs_alias'] = 'Alias-Pfad';
$_lang['customrequest.configs_alias_desc'] = 'Mit diesem Pfad wird der Anfang einer nicht gefundenen URI verglichen. Wenn beide Pfade übereinstimmen, dann wird diese Konfiguration benutzt. Wenn der Alias-Pfad nicht gesetzt ist, wird der Alias-Pfad der angegegeben Ressource herangezogen.';
$_lang['customrequest.configs_alias_generated'] = 'Generiert';
$_lang['customrequest.configs_alias_regex'] = 'Regulärer Ausdruck';
$_lang['customrequest.configs_context'] = 'Kontext';
$_lang['customrequest.configs_create'] = 'Neue Konfiguration';
$_lang['customrequest.configs_desc'] = 'Erstellen und ändern Sie Ihre CustomRequest-Konfigurationen.';
$_lang['customrequest.configs_desc_extended'] = "Die Konfigurationen werden in der Reihenfolge ausgeführt, in der sie in der Tabelle aufgeführt sind. Falls zwei Konfigurationen mit dem gleichen Alias-Pfad beginnen, dann wird die erste Konfiguration benutzt. Sie können Reihenfolge der Konfigurationen per Drag&amp;Drop ändern. Die Spalte 'Alias Pfad' wird grün markiert, wenn er aus einer Ressourcen ID generiert wird. Sie wird blau markiert, wenn sie einen gültigen Regulären Ausdruck enthält.";
$_lang['customrequest.configs_err_ns_alias_resourceid'] = 'Bitte einen Alias angegeben oder eine Ressource auswählen.';
$_lang['customrequest.configs_err_nv_alias_regex'] = 'Wenn keine Ressource ausgewählt ist, muss der Alias ein gültiger Regulärer Ausdruck mit Trennzeichen sein.';
$_lang['customrequest.configs_err_nv_regex'] = 'Der Reguläre Ausdruck muss Trennzeichen enthalten und gültig sein.';
$_lang['customrequest.configs_name'] = 'Konfigurations-Name';
$_lang['customrequest.configs_regex'] = 'Regulärer Ausdruck';
$_lang['customrequest.configs_regex_desc'] = 'Dieser optionale reguläre Ausdruck wird benutzt, um den zweiten Teils der nicht gefundenen URI aufzuteilen. Die Fundstücke werden in der gefundenen Reihenfolge den einzelnen Request-Parametern zugewiesen.';
$_lang['customrequest.configs_remove'] = 'Konfiguration Löschen';
$_lang['customrequest.configs_remove_confirm'] = 'Sind Sie sicher, dass sie diese CustomRequest Konfiguration löschen wollen?';
$_lang['customrequest.configs_resourceid'] = 'Ressource';
$_lang['customrequest.configs_resourceid_desc'] = 'Auf diese Ressource wird eine nicht gefundene URI bei erfolgreichem Vergleich mit dem Alias-Pfad umgeleitet.';
$_lang['customrequest.configs_update'] = 'Konfiguration Bearbeiten';
$_lang['customrequest.configs_urlparams'] = 'Request-Parameter';
$_lang['customrequest.configs_urlparams_desc'] = 'JSON kodiertes Array mit Request-/Get-/Post-Parameter Schlüsseln. Jedem Schlüssel wird ein Abschnitt des zweiten Teils (nach dem Alias-Pfad) der nicht gefundenen URI zugewiesen. Wenn kein regulärer Ausdruck angegeben ist, wird der zweite Teil am URI Trennzeichen `/` aufgeteilt';
$_lang['customrequest.debug_mode'] = 'Debug-Modus';
$_lang['customrequest.menu_home'] = 'CustomRequest';
$_lang['customrequest.menu_home_desc'] = 'Benutzerfreundliche URLs überall';
$_lang['customrequest.settings'] = '<i class="icon icon-cog"></i>';
$_lang['customrequest.settings_desc'] = 'Bearbeiten Sie die Einstellungen von CustomRequest. Sie können den Wert einer Systemeinstellung mit einem Doppelklick auf die ‚Wert‘-Tabellenzelle oder die Systemeinstellung mit einem Rechtsklick in der Tabellenzelle bearbeiten.';
$_lang['customrequest.systemsetting_key_err_nv'] = 'Sie dürfen nur Einstellungen mit dem Prefix customrequest bearbeiten.';
$_lang['customrequest.systemsetting_usergroup_err_nv'] = 'Nur Benutzer mit einer settings Berechtigung oder einer settings_customrequest Berechtigung können die Einstellungen ändern.';
