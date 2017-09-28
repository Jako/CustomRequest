### Calendar

With the **Date** configuration you could use an calendar snippet on the 
resource with the URI `calendar/date/`. The snippet on that resource would 
use the request parameters `year`, `month`, `day`, `title` to identify the 
event. An example URI triggering this configuration: 
`/calendar/date/2015/09/01/eventname.html`

The **Calendar** configuration sends the request parameters `year`, `month`, 
`day` to another snippet on the resource with the URI `calendar/`. Example 
triggering URI: `/calendar/2015/09/01/` [^1]

Name | Alias Path | Resource | URI Parameter | Regular Expression
--------------|------------|----------|---------------|-------------------
Date | calendar/date/ | | ["year", "month", "day", "title"] |
Calendar | calendar/ | | ["year", "month", "day"] |

[^1]: If you are using two or more nested aliases in your configs, the deeper alias should be defined before the narrower alias in the configs. You could drag and drop the configurations in the grid of the custom manager page. 

### Gallery

With the **Gallery** configuration you could use the Gallery snippet on the 
selected resource `Gallery Folder`. The Gallery snippet on that resource would 
use the request parameters `galAlbum`, `galItem` to identify the gallery and 
the image. An example URI triggering this configuration: `/gallery/01/02/`

Name | Alias Path | Resource | URI Parameter | Regular Expression
--------------|------------|----------|---------------|-------------------
Gallery |  | Gallery Folder | ["galAlbum", "galItem"] |

### Different URI

With the **Different URI** configuration you could call the resource `Test` 
with a complete different URI using the request parameters `parameter1`, 
`parameter2`. [^2]

Name | Alias Path | Resource | URI Parameter | Regular Expression
--------------|------------|----------|---------------|-------------------
Different URI | complete/different/uri/ | Test | ["parameter1", "parameter2"] |

[^2]: The Alias Path does not have to match the alias of the Resource.

### Regular Expression

You could even use regular expressions[^5] to set the request parameters [^3]

Name | Alias Path | Resource | URI Parameter | Regular Expression
--------------|------------|----------|---------------|-------------------
Expression | | Expression | ["string", "numeric"] | #(.*?)-(\d+)#

[^3]: This rule does not make much sense. If you have a real world example, please ...

### Pagination

The Alias Path field could be filled with a valid regular expression[^5] (the 
color of the grid field is changed to blue then[^4]) and the Resource field 
could stay empty. Thay way you could use one pagination configuration for all 
pagination calls on the page. The first subpattern part (`page/`) of the 
expression is stripped from the found pattern and the remaining string is used 
to identify the resource where the user is forwarded to later.

Name | Alias Path | Resource | URI Parameter | Regular Expression
--------------|------------|----------|---------------|-------------------
Pagination | #.*?(page/)# | | ["page"] | #(\d+)#

[^4]: The regular expression[^5] has to be valid and it should contain [delimiters](http://php.net/manual/en/regexp.reference.delimiters.php)

[^5]: To build and check regular expressions you could i.e. use the [regex101](https://regex101.com/) website.

## System Settings

CustomRequest uses the following system settings in the namespace `customrequest`:

Key | Description | Default
----|-------------|--------
customrequest.debug | Log debug information in the MODX error log | No

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
