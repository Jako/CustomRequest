#CustomRequest

Use pretty URLs everywhere in the MODX Revolution content management framework

##Features
CustomRequest is an effective tool for MODX Revolution to map pretty but not
found URLs to a MODX resource and set the request parameters by separating the
URI path at the URI separators or by a regular expression.

The first characters of the not found URI will be compared with the alias value
of each config entry. If found, this config entry is used. The alias is stripped
from the not found URI and the remaining string is used to set the request
parameters.

##Configuation
You could configure CustomRequest in a custom manager page in the extras menu. 
The CustomRequest configurations could be created on that page.

The configurations are executed in the order of the grid on this page. If there 
are two configurations starting with the same alias path, the first 
configuration is used. You can change the order of the configurations by 
drag and drop.

The following settings could be set in each configuration:

Key | Description
----|------------
Name | A name to identify this configuration.
Alias Path | The first characters of a not found URI are compared with this string. If both paths are matching, this configuration is used. If the alias path field is not set, the alias path of the selected resource in this form is used.
Resource | A not found URI is forwarded to this resource, if the current configuration is used.
URI Parameter | The request/get/post parameter keys, the divided second part of the not found URI are assigned to. If the Regular Expression field not set, the second part is divided at the URI separators `/`
Regular Expression | This optional regular expression is used to divide the second parts of the not found URI. The regular expression has to contain [delimiters](http://php.net/manual/en/regexp.reference.delimiters.php). The search results are assigned to the request parameters in the order of occurrence.

##Example Configurations

###Calendar

With the **Date** configuration you could use an calendar snippet on the resource with the URI `calendar/date/`. The snippet on that resource would use the request parameters `year`, `month`, `day`, `title` to identify the event. An example URI triggering this configuration: `/calendar/date/2015/09/01/eventname.html`

The **Calendar** configuration sends the request parameters `year`, `month`, `day` to another snippet on the resource with the URI `calendar/`. Example triggering URI: `/calendar/2015/09/01/` [^1]

Name | Alias Path | Resource | URI Parameter | Regular Expression
--------------|------------|----------|---------------|-------------------
Date | calendar/date/ | | ["year", "month", "day", "title"] |
Calendar | calendar/ | | ["year", "month", "day"] |

[^1]: If you are using two or more nested aliases in your configs, the deeper alias should be defined before the narrower alias in the configs.

###Gallery

With the **Gallery** configuration you could use a calendar snippet on the selected resource `Gallery Folder`. The Gallery snippet on that resource would use the request parameters `galAlbum`, `galItem` to identify the gallery and the image. An example URI triggering this configuration: `/gallery/01/02/`

Name | Alias Path | Resource | URI Parameter | Regular Expression
--------------|------------|----------|---------------|-------------------
Gallery |  | Gallery Folder | ["galAlbum", "galItem"] |

###Different URI

With the **Different URI** configuration you could call the resource `Test` with a complete different URI useing the request parameters `parameter1`, `parameter2`. [^2]

Name | Alias Path | Resource | URI Parameter | Regular Expression
--------------|------------|----------|---------------|-------------------
Different URI | complete/different/uri/ | Test | ["parameter1", "parameter2"] |

[^2]: The Alias Path does not have to match the alias of the Resource.

###Regular Expression

You could even use Regular Expressions to set the request parameters [^3]

Name | Alias Path | Resource | URI Parameter | Regular Expression
--------------|------------|----------|---------------|-------------------
Expression | | Expression | ["string", "numeric"] | #(.*?)-(\d+)#

[^3]: This rule does not make much sense. If you have a better one ...

##System Settings

The following parameter could be set in system settings

Parameter | Description
----------|------------
debug | Log debug information in MODX error log

<!-- Piwik -->
<script type="text/javascript">
  var _paq = _paq || [];
  _paq.push(['trackPageView']);
  _paq.push(['enableLinkTracking']);
  (function() {
    var u="//piwik.partout.info/";
    _paq.push(['setTrackerUrl', u+'piwik.php']);
    _paq.push(['setSiteId', 16]);
    var d=document, g=d.createElement('script'), s=d.getElementsByTagName('script')[0];
    g.type='text/javascript'; g.async=true; g.defer=true; g.src=u+'piwik.js'; s.parentNode.insertBefore(g,s);
  })();
</script>
<noscript><p><img src="//piwik.partout.info/piwik.php?idsite=16" style="border:0;" alt="" /></p></noscript>
<!-- End Piwik Code -->
