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
$_lang['customrequest.configs_alias_desc'] = 'Mit diesem Pfad wird der Anfang einer nicht gefundenen URI verglichen. Wenn beide Pfade übereinstimmen, dann wird diese Konfiguration benutzt. Wenn der Alias-Pfad nicht gesetzt ist, wird der Alias-Pfad der angegebenen Ressource herangezogen.';
$_lang['customrequest.configs_alias_generated'] = 'Generiert';
$_lang['customrequest.configs_alias_regex'] = 'Regulärer Ausdruck';
$_lang['customrequest.configs_context'] = 'Kontext';
$_lang['customrequest.configs_create'] = 'Neue Konfiguration';
$_lang['customrequest.configs_desc'] = 'Erstellen und ändern Sie Ihre CustomRequest-Konfigurationen.';
$_lang['customrequest.configs_desc_extended'] = "Die Konfigurationen werden in der Reihenfolge des Rasters ausgeführt. Wenn es zwei Konfigurationen gibt, die mit demselben Alias-Pfad beginnen, wird die erste Konfiguration verwendet. Sie können die Reihenfolge der Konfigurationen mit Drag and Drop ändern. Die Spalte ‚Alias-Pfad‘ wird mit grünem Text angezeigt, wenn sie aus einer Ressourcen-ID erstellt wurde. Sie wird mit blauem Text angezeigt, wenn sie einen gültigen regulären Ausdruck enthält.";
$_lang['customrequest.configs_duplicate'] = 'Konfiguration duplizieren';
$_lang['customrequest.configs_err_nfs'] = 'Konfiguration mit der ID [[+id]] nicht gefunden.';
$_lang['customrequest.configs_err_ns'] = 'Konfiguration nicht angegeben.';
$_lang['customrequest.configs_err_ns_alias_resourceid'] = 'Bitte einen Alias angeben oder eine Ressource auswählen.';
$_lang['customrequest.configs_err_nv_alias_regex'] = 'Wenn keine Ressource ausgewählt ist, muss der Alias ein gültiger regulärer Ausdruck mit Begrenzungszeichen sein.';
$_lang['customrequest.configs_err_nv_regex'] = 'Der reguläre Ausdruck muss Begrenzungszeichen enthalten und gültig sein.';
$_lang['customrequest.configs_err_nv_urlparams'] = 'Die URI-Parameter müssen ein JSON-Array mit Parameternamen sein, z. B. ["year", "month"].';
$_lang['customrequest.configs_err_remove'] = 'Beim Löschen der Konfiguration ist ein Fehler aufgetreten.';
$_lang['customrequest.configs_err_save'] = 'Beim Speichern der Konfiguration ist ein Fehler aufgetreten.';
$_lang['customrequest.configs_name'] = 'Konfigurationsname';
$_lang['customrequest.configs_regex'] = 'Regulärer Ausdruck';
$_lang['customrequest.configs_regex_desc'] = 'Dieser optionale reguläre Ausdruck wird benutzt, um den zweiten Teil der nicht gefundenen URI aufzuteilen. Die Fundstücke werden in der gefundenen Reihenfolge den einzelnen Request-Parametern zugewiesen.';
$_lang['customrequest.configs_remove'] = 'Konfiguration löschen';
$_lang['customrequest.configs_remove_confirm'] = 'Sind Sie sicher, dass Sie diese CustomRequest-Konfiguration löschen wollen?';
$_lang['customrequest.configs_resourceid'] = 'Ressource';
$_lang['customrequest.configs_resourceid_desc'] = 'Auf diese Ressource wird eine nicht gefundene URI bei erfolgreichem Vergleich mit dem Alias-Pfad umgeleitet.';
$_lang['customrequest.configs_update'] = 'Konfiguration bearbeiten';
$_lang['customrequest.configs_urlparams'] = 'URI-Parameter';
$_lang['customrequest.configs_urlparams_desc'] = 'Die Request-Parameterschlüssel, denen die Teile der nicht gefundenen URI nach dem Alias-Pfad zugewiesen werden. Wenn das Feld ‚Regulärer Ausdruck‘ nicht gesetzt ist, wird die URI an den URI-Trennzeichen `/` aufgeteilt.';
$_lang['customrequest.debug_mode'] = 'Debug-Modus';
$_lang['customrequest.duplicate'] = 'Duplikat von';
$_lang['customrequest.menu_home'] = 'CustomRequest';
$_lang['customrequest.menu_home_desc'] = 'Benutzerfreundliche URLs überall';
$_lang['customrequest.refresh_cache'] = 'Leeren des [[+packagename]]-Caches';
$_lang['customrequest.settings'] = '<i class="icon icon-cog"></i>';
$_lang['customrequest.settings_desc'] = 'Bearbeiten Sie die Einstellungen von CustomRequest. Sie können den Wert einer Systemeinstellung mit einem Doppelklick auf die ‚Wert‘-Tabellenzelle oder die Systemeinstellung mit einem Rechtsklick in der Tabellenzelle bearbeiten.';
$_lang['customrequest.systemsetting_key_err_nv'] = 'Sie dürfen nur Einstellungen mit dem Präfix customrequest. bearbeiten.';
$_lang['customrequest.systemsetting_usergroup_err_nv'] = 'Nur Benutzer mit einer settings-Berechtigung oder einer customrequest_settings-Berechtigung können die Einstellungen ändern.';
