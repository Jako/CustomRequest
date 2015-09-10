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

Configuation
--------------------------------------------------------------------------------
You could configure CustomRequest in a custom manager page in the extras menu.
The CustomRequest could be created on that page.

The configurations are executed in the order of the grid on this page. If there
are two configurations starting with the same alias path, the first
configuration is used. You can change the order of the configurations by
drag&drop.

The following settings could be used in each configuration:

Key           | Description
--------------|------------
Name          | A name to identify this configuration.
Alias Path    | The first characters of a not found URI are compared with this
              | string. If both paths are matching, this configuration is used.
              | If the alias path field is not set, the alias path of the
              | selected resource in this form is used.
Resource      | A not found URI is forwarded to this resource, if the current
              | configuration is used.
URI Parameter | The request/get/post parameter keys, the divided second part
              | of the not found URI are assigned to. If the Regular Expression
              | field not set, the second part is divided at the URI
              | separators `/`
Regular Expr. | This optional regular expression is used to divide the second
              | parts of the not found URI. The search results are assigned to
              | the request parameters in the order of occurrence.

Examples
--------------------------------------------------------------------------------

#### Calendar

Name     | Alias Path     | Resource | URI Parameter                     | Regular Expression
--------------------------|----------|-----------------------------------|-------------------
Date     | calendar/date/ | -        | ["year", "month", "day", "title"] |
Calendar | calendar/      | -        | ["year", "month", "day"]          |

1. If you are using two or more nested aliases in your configs, the deeper alias should be defined before the narrower alias in the configs.

#### Gallery

Name    | Alias Path | Resource | URI Parameter           | Regular Expression
--------|------------|----------|-------------------------|-------------------
Gallery |            | Gallery  | ["galAlbum", "galItem"] |

#### Test

Name | Alias Path                | Resource | URI Parameter                | Regular Expression
---- |---------------------------|----------|------------------------------|-------------------
Test | completely/different/uri/ | Test     | ["parameter1", "parameter2"] |

1. The Alias Path does not have to match the alias of the Resource.

#### Regular Expression

Name       | Alias Path | Resource   | URI Parameter         | Regular Expression
-----------|------------|------------|-----------------------|-------------------
Expression |            | Expression | ["string", "numeric"] | (.*?)-(\d+)
