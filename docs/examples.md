### Calendar

With the **Date** configuration you can use a calendar snippet on the resource
`Date` with the URI `calendar/date/`. The snippet on that resource uses the
request parameters `year`, `month`, `day`, `title` to identify the event. An
example URI triggering this configuration:
`/calendar/date/2015/09/01/eventname.html`

The **Calendar** configuration sends the request parameters `year`, `month`,
`day` to another snippet on the resource `Calendar` with the URI `calendar/`.
Example triggering URI: `/calendar/2015/09/01/` [^1]

Name | Alias Path | Resource | URI Parameter | Regular Expression
--------------|------------|----------|---------------|-------------------
Date | calendar/date/ | Date | ["year", "month", "day", "title"] |
Calendar | calendar/ | Calendar | ["year", "month", "day"] |

[^1]: If you are using two or more nested aliases in your configurations, the deeper alias has to be defined before the narrower alias. You can drag and drop the configurations in the grid of the custom manager page.

### Gallery

With the **Gallery** configuration you can use the Gallery snippet on the
selected resource `Gallery Folder`. The Gallery snippet on that resource uses
the request parameters `galAlbum`, `galItem` to identify the gallery and the
image. An example URI triggering this configuration: `/gallery/01/02/`

Name | Alias Path | Resource | URI Parameter | Regular Expression
--------------|------------|----------|---------------|-------------------
Gallery |  | Gallery Folder | ["galAlbum", "galItem"] |

### Different URI

With the **Different URI** configuration you can call the resource `Test` with
a completely different URI using the request parameters `parameter1`,
`parameter2`. [^2]

Name | Alias Path | Resource | URI Parameter | Regular Expression
--------------|------------|----------|---------------|-------------------
Different URI | complete/different/uri/ | Test | ["parameter1", "parameter2"] |

[^2]: The Alias Path does not have to match the alias of the Resource.

### Regular Expression

You can even use regular expressions[^5] to set the request parameters. [^3]

Name | Alias Path | Resource | URI Parameter | Regular Expression
--------------|------------|----------|---------------|-------------------
Expression | | Expression | ["string", "numeric"] | #(.*?)-(\d+)#

[^3]: This rule does not make much sense. If you have a real world example, please open an issue in the [GitHub repository](https://github.com/Jako/CustomRequest/issues).

### Pagination

The Alias Path field can be filled with a valid regular expression[^5] (the
grid value is shown with blue text then[^4]) and the Resource field can stay
empty. That way you can use one pagination configuration for all pagination
calls on the site. The first subpattern (`page/`) of the expression is stripped
from the found pattern and the remaining string is used to identify the
resource the user is forwarded to.

Name | Alias Path | Resource | URI Parameter | Regular Expression
--------------|------------|----------|---------------|-------------------
Pagination | #.*?(page/)# | | ["page"] | #(\d+)#

[^4]: The regular expression[^5] has to be valid and it has to contain [delimiters](https://www.php.net/manual/en/regexp.reference.delimiters.php).

[^5]: To build and check regular expressions you can use e.g. the [regex101](https://regex101.com/) website.

## System Settings

CustomRequest uses the following system settings in the namespace `customrequest`:

Key | Description | Default
----|-------------|--------
customrequest.debug | Log debug information in the MODX error log | No
