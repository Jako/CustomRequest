#CustomRequest
##Use pretty URLs everywhere

CustomRequest is an effective tool for MODX Revolution to map pretty but not
found URLs to a MODX resource and set the request parameter by separating the
URI path at the URI separators or by a regular expression.

The first characters of the not found URI will be compared with the alias value
of each config entry. If found, this config entry is used. The alias is stripped
from the not found URI and the remaining string is used to set the request
parameters.

For more information please read the [documentation](http://jako.github.io/CustomRequest/).
