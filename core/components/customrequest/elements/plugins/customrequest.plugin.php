<?php
/**
 * CustomRequest plugin
 *
 * @package customrequest
 * @subpackage plugin
 *
 * @var modX $modx
 */

$customrequestCorePath = $modx->getOption('customrequest.core_path', null, $modx->getOption('core_path') . 'components/customrequest/');
/** @var CustomRequest $customrequest */
$customrequest = $modx->getService('customrequest', 'CustomRequest', $customrequestCorePath . 'model/customrequest/',array(
    'core_path' => $corePath
));

$requestUri = trim(strtok($_SERVER['REQUEST_URI'], '?'), '/');

$eventName = $modx->event->name;
switch ($eventName) {
    case 'OnSiteRefresh':
    case 'OnDocFormSave':
    case 'OnDocFormDelete':
    case 'OnDocPublished':
    case 'OnDocUnPublished':
        $customrequest->reset();
        break;
    case 'OnPageNotFound':
        if ($modx->context->get('key') !== 'mgr') {
            $customrequest->initialize();
            if ($customrequest->searchAliases($requestUri)) {
                $customrequest->setRequest();
            }
        }
        break;
    case 'OnWebPagePrerender':
        // TODO: replace not friendly URL parameter (for URLs with a valid CustomRequest config) with friendly ones
        // A lot easier, if there would be an onMakeUrl event.
        break;
}

return;
