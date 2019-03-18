<?php
/**
 * CustomRequest plugin
 *
 * @package customrequest
 * @subpackage plugin
 *
 * @var modX $modx
 */

$eventName = $modx->event->name;

$corePath = $modx->getOption('customrequest.core_path', null, $modx->getOption('core_path') . 'components/customrequest/');
/** @var CustomRequest $customrequest */
$customrequest = $modx->getService('customrequest', 'CustomRequest', $corePath . 'model/customrequest/', array(
    'core_path' => $corePath
));

$requestParamAlias = $modx->getOption('request_param_alias', null, 'q');
$requestUri = trim(strtok($_REQUEST[$requestParamAlias], '?'), '/');

switch ($eventName) {
    case 'OnSiteRefresh':
    case 'OnDocFormSave':
    case 'OnDocFormDelete':
    case 'OnDocPublished':
    case 'OnDocUnPublished':
        $customrequest->reset();
        break;
    case 'OnPageNotFound':
        $customrequest->initialize();
        if ($modx->context->get('key') !== 'mgr') {
            if ($customrequest->searchAliases($requestUri)) {
                $customrequest->setRequest();
            }
        }
        break;
    case 'OnWebPagePrerender':
        /**
         * TODO: replace not friendly URL parameter (for URLs with a valid CustomRequest config) with friendly ones
         * A lot easier, if there would be an onMakeUrl event
         */
        break;
}

return;
