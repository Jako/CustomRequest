## How it works

CustomRequest works as routing plugin and maps not found URLs to a MODX resource
and set additional request parameters by separating the URI path at the URI
separators or by a regular expression.

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

## Custom Manager Page

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
Alias Path | <p>Normally the first characters of a not found URI are compared with this string. If both strings are matching, this configuration is used. If the alias path field is not set, the alias path of the selected resource in this form is used **(The grid value is shown with green text then)**. The alias path is stripped from the not found URI and the remaining string is used to set the request parameters.</p>As a second option, this field could contain a valid regular expression[^1] **(The grid value is shown with blue text then)**. The regular expression has to contain [delimiters](https://www.php.net/manual/en/regexp.reference.delimiters.php). The not found URI will be matched with that expression. If matched, this config entry is used. The first subpattern part of the expression is stripped from the found pattern and the remaining string is used to identify the resource where the user is forwarded to. At least the found pattern is removed from the not found URI and the remaining string is used to set the request parameters.
Resource | A not found URI is forwarded to this resource, if the current configuration is used and if the alias path does not contain a regular expression.
URI Parameter | The request/get/post parameter keys, the divided second part of the not found URI are assigned to. If the Regular Expression field not set, the second part is divided at the URI separators `/`
Regular Expression | This optional regular expression[^1] is used to divide the second parts of the not found URI. The regular expression has to contain [delimiters](https://www.php.net/manual/en/regexp.reference.delimiters.php). The search results are assigned to the request parameters in the order of occurrence.

[^1]: To build and check regular expressions you could i.e. use the [regex101](https://regex101.com/) website. Please use the code generator, since this generator creates the regular expression with delimiters.
