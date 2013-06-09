CustomRequest
================================================================================

Beautiful URLs everywhere
for the MODX Revolution content management framework

Features
--------------------------------------------------------------------------------
CustomRequest is an effective tool for MODX Revolution to write pretty URLs and
map those URLs to a MODX resource and set the request parameter by separating
the URI path at the URI separators or by a regular expression.

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
	'id' => 200,
	'alias' => 'completely/different/uri/',
	'urlParams' => array('parameter1', 'parameter2'),
	'regEx' => '#(.*?)-(.*)#i'
);
```

One of the array keys 'id' or 'alias' is required.

The following keys could be used in the array:

Key       | Description
----------|------------
id        | The id of a MODX resource
alias     | The string the first signs of the not found uri is compared with.
urlParams | The request parameter keys, the divided second parts of the not found uri are assigned to.
regex     | If set, this regular expression is used to divide the second parts of the not found uri. Else it is divided at uri separators (/)

Look into the folder core/components/customrequest/configs.example for

Notes
--------------------------------------------------------------------------------
1. If you are using two or more nested aliases in your configs, the deeper alias should be defined before the narrower alias in the configs. See `core/components/customrequest/configs.example/calendar.config.inc.php`

