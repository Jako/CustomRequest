<?php
/**
 * CustomRequest
 *
 * Copyright 2013 by Thomas Jakobi <thomas.jakobi@partout.info>
 *
 * @package customrequest
 * @subpackage plugin
 *
 * CustomRequest plugin
 */
$customrequestCorePath = $modx->getOption('customrequest.core_path', null, $modx->getOption('core_path') . 'components/customrequest/');

$requestUri = trim(strtok($_SERVER['REQUEST_URI'],'?'),'/');

$customrequest = $modx->getService('customrequest', 'CustomRequest', $customrequestCorePath . 'model/customrequest/', $scriptProperties);
$customrequest->initialize();

$eventName = $modx->event->name;
switch ($eventName) {
    case 'OnPageNotFound':
        if ($customrequest->searchAliases($requestUri)) {
            $customrequest->setRequest();
        }
        break;
    case 'OnWebPagePrerender':
        /* TODO: replace not friendly URL parameter (for URLs with a valid CustomRequest config) with friendly ones */
        break;
}

return;