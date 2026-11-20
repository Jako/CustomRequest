## How it works

CustomRequest works as a routing plugin. It maps not found URLs to a MODX
resource and sets additional request parameters by separating the URI path at
the URI separators or by a regular expression.

The first characters of the not found URI are compared with the alias path of
each configuration. If they match, this configuration is used. The alias path
is stripped from the not found URI and the remaining string is used to set the
request parameters.

If the system setting `customrequest.match_path_boundary` is enabled, the alias
path has to match at a path boundary of the not found URI, e.g. the alias path
`blog` matches `blog/2024/05` but not `blogroll/2024`. The setting is enabled in
new installations and disabled in installations updated from a version before
1.4.0.

Alternatively, if no resource is selected, the alias path of a configuration
can contain a valid regular expression and the not found URI is matched with
that expression. If it
matches, this configuration is used. The first subpattern of the expression is
stripped from the found pattern and the remaining string is used to identify
the resource the user is forwarded to. Finally, the found pattern is removed
from the not found URI and the remaining string is used to set the request
parameters.

A not found URI is only forwarded to a published and not deleted resource. Like
in MODX, users with the `view_unpublished` permission are forwarded to
unpublished resources too. If the resource is not available, the following
configurations are checked.

In multilingual installations, e.g. with Babel, a leading culture key of the
current context (like `de/`) is removed from the not found URI before it is
compared.

### Request parameters

The remaining string is divided at the URI separators `/` (or by the regular
expression of the configuration) and the parts are assigned to the keys of the
URI Parameters field in their order. Parts without a key are assigned to `p1`,
`p2` and so on, according to their position, e.g. the third part is assigned to
`p3`. A trailing extension of the MODX HTML content type (e.g. `.html`) is
removed from the remaining string before.

The parameters are set in `$_GET` and `$_REQUEST`, so a snippet on the resource
can use them like normal request parameters. Like in normal request parameters,
MODX tags are removed from the values. If the regular expression of a
configuration does not match the remaining string, the request is not
forwarded.

## Custom Manager Page

You can configure CustomRequest in a custom manager page in the extras menu.
The CustomRequest configurations are created on that page.

The configurations are executed in the order of the grid on this page. If there
are two configurations starting with the same alias path, the first
configuration is used. You can change the order of the configurations by
drag and drop.

The following settings can be set in each configuration:

| Key                | Description |
|--------------------|-------------|
| Name               | A name to identify this configuration. |
| Alias Path         | <p>The first characters of a not found URI are compared with this string. If they match, this configuration is used. If the system setting `customrequest.match_path_boundary` is enabled, this string has to match at a path boundary of the not found URI. If the alias path field is not set, the alias path of the selected resource in this form is used **(the grid value is shown with green text then)**. The alias path is stripped from the not found URI and the remaining string is used to set the request parameters.</p>Alternatively, this field can contain a valid regular expression[^1] **(the grid value is shown with blue text then)**. The regular expression has to contain [delimiters](https://www.php.net/manual/en/regexp.reference.delimiters.php). The not found URI is matched with that expression. If it matches, this configuration is used. The first subpattern of the expression is stripped from the found pattern and the remaining string is used to identify the resource the user is forwarded to. Finally, the found pattern is removed from the not found URI and the remaining string is used to set the request parameters.<p>If no resource is selected, the alias path has to be a regular expression. If a resource is selected, the alias path can't be a regular expression.</p> |
| Resource           | A not found URI is forwarded to this resource if the current configuration is used. If the resource is deleted or unpublished (and the user is not allowed to view unpublished resources), the following configurations are checked. |
| URI Parameters     | A JSON array of request parameter keys, e.g. `["year", "month"]`. The parts of the not found URI after the alias path are assigned to these keys in their order. If the Regular Expression field is not set, the URI is divided at the URI separators `/`. |
| Regular Expression | This optional regular expression[^1] is used to divide the remaining string of the not found URI. The regular expression has to contain [delimiters](https://www.php.net/manual/en/regexp.reference.delimiters.php). The subpatterns are assigned to the request parameters in the order of occurrence. Named subpatterns are assigned in their order as well, their names are not used. |

[^1]: To build and check regular expressions you can use e.g. the [regex101](https://regex101.com/) website. Please use the code generator, since this generator creates the regular expression with delimiters.

## System Settings

CustomRequest uses the following system settings in the namespace `customrequest`:

Key | Description | Default
----|-------------|--------
customrequest.debug | Log debug information in the MODX error log | No
customrequest.match_path_boundary | The alias path has to match at a path boundary of the not found URI, e.g. the alias path `blog` matches `blog/2024` but not `blogroll/2024`. If disabled, only the first characters of the not found URI are compared with the alias path. | Yes[^2]

[^2]: No in installations updated from a version before 1.4.0.
