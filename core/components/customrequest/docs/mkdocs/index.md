#CustomRequest

Use pretty URLs everywhere in the MODX Revolution frontend. 

### Requirements

* MODX Revolution 2.3+
* PHP v5.4+

### Features
CustomRequest is an effective routing plugin for MODX Revolution to map pretty 
but not found URLs to a MODX resource and set the request parameters by 
separating the URI path at the URI separators or by a regular expression.

As standard option the first characters of the not found URI will be compared 
with the alias value of each config entry. If found, this config entry is used. 
The alias path is stripped from the not found URI and the remaining string is 
used to set the request parameters.

As a second option, the alias value of a config entry could contain a valid 
regular expression and the not found URI will be matched with that expression. 
If matched, this config entry is used. The first subpattern part of the 
expression is stripped from the found pattern and the remaining  string is used 
to identify the resource where the user is forwarded to later. At least the 
found pattern is removed from the not found URI and the remaining string is 
used to set the request parameters.

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
