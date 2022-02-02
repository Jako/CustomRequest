<?php
/**
 * CustomRequest connector
 *
 * @package customrequest
 * @subpackage connector
 *
 * @var modX $modx
 */

require_once dirname(__FILE__, 4) . '/config.core.php';
require_once MODX_CORE_PATH . 'config/' . MODX_CONFIG_KEY . '.inc.php';
require_once MODX_CONNECTORS_PATH . 'index.php';

$corePath = $modx->getOption('customrequest.core_path', null, $modx->getOption('core_path') . 'components/customrequest/');
/** @var CustomRequest $customrequest */
$customrequest = $modx->getService('customrequest', 'CustomRequest', $corePath . 'model/customrequest/', [
    'core_path' => $corePath
]);

// Handle request
$modx->request->handleRequest([
    'processors_path' => $customrequest->getOption('processorsPath'),
    'location' => ''
]);
