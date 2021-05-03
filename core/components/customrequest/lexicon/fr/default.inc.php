<?php
/**
 * Default Lexicon Entries for CustomRequest
 *
 * @package customrequest
 * @subpackage lexicon
 */
$_lang['customrequest'] = 'CustomRequest';
$_lang['customrequest.menu_home'] = 'CustomRequest';
$_lang['customrequest.menu_home_desc'] = 'Use friendly URLs everywhere';
$_lang['customrequest.configs'] = 'Configurations';
$_lang['customrequest.configs_alias'] = 'Alias Path';
$_lang['customrequest.configs_alias_desc'] = 'The first characters of a not found URI are compared with this string. If both paths are matching, this configuration is used. If the alias path field is not set, the alias path of the selected resource in this form is used.';
$_lang['customrequest.configs_alias_generated'] = 'Generated';
$_lang['customrequest.configs_alias_regex'] = 'Regular Expression';
$_lang['customrequest.configs_context'] = 'Context';
$_lang['customrequest.configs_create'] = 'New Configuration';
$_lang['customrequest.configs_desc'] = 'Create and modify your CustomRequest configurations.';
$_lang['customrequest.configs_desc_extended'] = "The configurations are executed in the order of the grid. If there are two configurations starting with the same alias path, the first configuration is used. You can change the order of the configurations by drag&amp;drop. The column 'Alias Path' is shown with green text, when it is generated from a Resource ID. It is shown with blue text, if it contains a valid regular expression.";
$_lang['customrequest.configs_name'] = 'Configuration Name';
$_lang['customrequest.configs_regex'] = 'Regular Expression';
$_lang['customrequest.configs_regex_desc'] = 'This optional regular expression is used to divide the second parts of the not found URI. The search results are assigned to the request parameters in the order of occurrence.';
$_lang['customrequest.configs_remove'] = 'Remove Configuration';
$_lang['customrequest.configs_remove_confirm'] = 'Are you sure you want to delete this CustomRequest Configuration?';
$_lang['customrequest.configs_resourceid'] = 'Resource';
$_lang['customrequest.configs_resourceid_desc'] = 'A not found URI is forwarded to this resource, if the current configuration is used.';
$_lang['customrequest.configs_update'] = 'Update Configuration';
$_lang['customrequest.configs_urlparams'] = 'URI Parameter';
$_lang['customrequest.configs_urlparams_desc'] = 'The request/get/post parameter keys, the divided second part of the not found URI are assigned to. If the Regular Expression field not set, the second part is divided at the URI separators \'/\'';
$_lang['customrequest.settings'] = '<i class="icon icon-cog"></i>';
$_lang['customrequest.settings_desc'] = 'Edit the settings of CustomRequest. You can edit the value of a system setting by double-clicking on the \'Value\' table cell or by right-clicking in the table cell.';
$_lang['customrequest.debug_mode'] = 'Debug Mode';
$_lang['customrequest.systemsetting_key_err_nv'] = 'You could only edit settings with the prefix customrequest.';
$_lang['customrequest.systemsetting_usergroup_err_nv'] = 'Only users with a settings permission or a settings_customrequest permission are allowed to change settings.';
$_lang['customrequest.configs_err_ns_alias_resourceid'] = 'Please fill the alias and/or select a resource.';
$_lang['customrequest.configs_err_nv_alias_regex'] = 'If no resource is selected, the alias has to be a valid regular expression containing delimiters.';
$_lang['customrequest.configs_err_nv_regex'] = 'The regular expression has to contain delimiters and it to be valid.';
