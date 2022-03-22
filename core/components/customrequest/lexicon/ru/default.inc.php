<?php
/**
 * Default Lexicon Entries for CustomRequest
 *
 * @package customrequest
 * @subpackage lexicon
 */
$_lang['customrequest'] = 'CustomRequest';
$_lang['customrequest.configs'] = 'Настройки';
$_lang['customrequest.configs_alias'] = 'Путь псевдонима';
$_lang['customrequest.configs_alias_desc'] = 'Первые символы не найденного URI сравниваются с этой строкой. Если оба пути совпадают, то используется эта конфигурация. Если поле пути псевдонима не установлено, используется путь к псевдонимам выбранного ресурса в этой форме.';
$_lang['customrequest.configs_alias_generated'] = 'Сгенерировать';
$_lang['customrequest.configs_alias_regex'] = 'Регулярное выражение';
$_lang['customrequest.configs_context'] = 'Контекст';
$_lang['customrequest.configs_create'] = 'Новая конфигурация';
$_lang['customrequest.configs_desc'] = 'Создание и изменение конфигураций CustomRequest.';
$_lang['customrequest.configs_desc_extended'] = "Конфигурации выполняются в порядке таблицы. Если есть две конфигурации, начиная с одного и того же псевдонима, используется первая конфигурация. Вы можете изменить порядок конфигурации, перетащите&amp;падение. Столбец 'Путь Алиаса' отображается с зеленым текстом, когда он генерируется из идентификатора ресурса. Он отображается с синим текстом, если он содержит правильное регулярное выражение.";
$_lang['customrequest.configs_err_ns_alias_resourceid'] = 'Пожалуйста, заполните псевдоним и/или выберите ресурс.';
$_lang['customrequest.configs_err_nv_alias_regex'] = 'Если не выбран ресурс, псевдоним должен быть действительным регулярным выражением, содержащим разделители.';
$_lang['customrequest.configs_err_nv_regex'] = 'Регулярное выражение должно содержать разделители и быть действительным.';
$_lang['customrequest.configs_name'] = 'Имя конфигурации';
$_lang['customrequest.configs_regex'] = 'Регулярное выражение';
$_lang['customrequest.configs_regex_desc'] = 'Это необязательное регулярное выражение используется для разделения второй части не найденного URI. Результаты поиска назначаются для параметров запроса в порядке появления.';
$_lang['customrequest.configs_remove'] = 'Удалить конфигурацию';
$_lang['customrequest.configs_remove_confirm'] = 'Вы уверены, что хотите удалить эту конфигурацию CustomRequest?';
$_lang['customrequest.configs_resourceid'] = 'Ресурс';
$_lang['customrequest.configs_resourceid_desc'] = 'Не найденный URI перенаправлен на этот ресурс, если используется текущая конфигурация.';
$_lang['customrequest.configs_update'] = 'Обновить конфигурацию';
$_lang['customrequest.configs_urlparams'] = 'Параметр URI';
$_lang['customrequest.configs_urlparams_desc'] = 'Ключи параметра request/get/post, разделенные на вторую часть не найденного URI, назначаются. Если поле Регулярное выражение не установлено, вторая часть делится на разделители URI';
$_lang['customrequest.debug_mode'] = 'Режим отладки';
$_lang['customrequest.menu_home'] = 'CustomRequest';
$_lang['customrequest.menu_home_desc'] = 'Использовать дружественные URL везде';
$_lang['customrequest.settings'] = '<i class="icon icon-cog"></i>';
$_lang['customrequest.settings_desc'] = 'Редактировать настройки CustomRequest. Вы можете редактировать значение системной настройки, дважды щелкнув по ячейке таблицы «значений» или щелкнув правой кнопкой мыши по ячейке таблицы.';
$_lang['customrequest.systemsetting_key_err_nv'] = 'Вы можете редактировать настройки только с помощью обычного запроса префикса.';
$_lang['customrequest.systemsetting_usergroup_err_nv'] = 'Разрешается изменять настройки только пользователям с разрешением настроек или разрешением settings_customrequest.';
