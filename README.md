CustomRequest
================================================================================

Use pretty URLs everywhere.
for the MODX Revolution content management framework

Features
--------------------------------------------------------------------------------
CustomRequest is an effective tool for MODX Revolution to map pretty but not
found URLs to a MODX resource and set the request parameter by separating the
URI path at the URI separators or by a regular expression.

The first characters of the not found URI will be compared with the alias value
of each config entry. If found, this config entry is used. The alias is stripped
from the not found URI and the remaining string is used to set the request
parameters.

Installation
--------------------------------------------------------------------------------
MODX Package Management

Parameters
--------------------------------------------------------------------------------
The following parameters could be set in system settings

Parameter   | Description
------------|------------
debug       | Log debug information in MODX error log
configsPath | The folder where the plugin config files are load from

Config Files
--------------------------------------------------------------------------------
Each entry in a config file could contain the following lines

```php
$settings['test'] = array(
	'resourceId' => 200,
	'alias' => 'completely/different/uri/',
	'urlParams' => array('parameter1', 'parameter2'),
	'regEx' => '#(.*?)-(.*)#i'
);
```

One of the array keys 'resourceId' or 'alias' is required.

The following keys could be used in the array:

Key        | Description
-----------|------------
resourceId | The id of a MODX resource, the not found URI is forwarded to.
alias      | The first characters the not found URI is compared with. If found, this config is used and comparing is stopped.
urlParams  | The request parameter keys, the divided second parts of the not found URI are assigned to.
regex      | If set, this regular expression is used to divide the second parts of the not found URI. If not set, it is divided at the URI separators `/`.

Look into the folder `core/components/customrequest/configs.example` for example
config files.

Notes
--------------------------------------------------------------------------------
1. If you are using two or more nested aliases in your configs, the deeper alias should be defined before the narrower alias in the configs. See `core/components/customrequest/configs.example/calendar.config.inc.php`

