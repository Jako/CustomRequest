# CustomRequest

Use pretty URLs everywhere

## Features

CustomRequest is an effective routing plugin for MODX Revolution to map pretty
but not found URLs to a MODX resource and to set the request parameters by
separating the URI path at the URI separators or by a regular expression.

By default, the first characters of the not found URI are compared with the
alias path of each configuration. If they match, this configuration is used.
If the system setting `customrequest.match_path_boundary` is enabled, the alias
path has to match at a path boundary of the not found URI. The alias path is
stripped from the not found URI and the remaining string is used to set the
request parameters.

Alternatively, the alias path of a configuration can contain a valid regular
expression and the not found URI is matched with that expression. If it
matches, this configuration is used. The first subpattern of the expression is
stripped from the found pattern and the remaining string is used to identify
the resource the user is forwarded to. Finally, the found pattern is removed
from the not found URI and the remaining string is used to set the request
parameters.

## Installation

MODX Package Management

## Documentation

For more information please read the documentation on https://jako.github.io/CustomRequest/

## GitHub Repository

https://github.com/Jako/CustomRequest
