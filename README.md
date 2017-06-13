# CustomRequest
## Use pretty URLs everywhere

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

For more information please read the [documentation](http://jako.github.io/CustomRequest/).
