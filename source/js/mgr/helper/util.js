CustomRequest.util.htmlRenderer = function (value) {
    return (value === undefined || value === null) ? '' : MODx.util.safeHtml(String(value), '<span>');
}
