<?php
/**
 * CustomRequest Connector
 *
 * @package customrequest
 */
require_once dirname(dirname(dirname(dirname(__FILE__)))) . '/config.core.php';
require_once MODX_CORE_PATH . 'config/' . MODX_CONFIG_KEY . '.inc.php';
require_once MODX_CONNECTORS_PATH . 'index.php';

$corePath = $modx->getOption('customrequest.core_path', null, $modx->getOption('core_path') . 'components/customrequest/');

$modx->getService(
    'customrequest',
    'CustomRequest',
    $corePath . 'model/customrequest/',
    array(
        'core_path' => $corePath
    )
);

/* handle request */
$modx->request->handleRequest(array(
    'processors_path' => $modx->customrequest->getOption('processorsPath'),
    'location' => '',
));