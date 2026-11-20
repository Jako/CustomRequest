<?php
/**
 * Resolve version changes
 *
 * @package customrequest
 * @subpackage build
 *
 * @var array $options
 * @var xPDOTransport $transport
 */

/** @var modX $modx */
$modx = $transport->xpdo;
$modx->log(xPDO::LOG_LEVEL_INFO, 'Running the version resolver...');

$success = true;

if (!function_exists('changeSettingValue')) {
    /**
     * Change the value of a system setting.
     *
     * @param modX $modx
     * @param string $key
     * @param string $value
     */
    function changeSettingValue($modx, $key, $value)
    {
        /** @var modSystemSetting $setting */
        $setting = $modx->getObject('modSystemSetting', [
            'key' => $key
        ]);
        if (!$setting) {
            return;
        }
        $setting->set('value', $value);
        $setting->save();
    }
}

switch ($options[xPDOTransport::PACKAGE_ACTION]) {
    case xPDOTransport::ACTION_INSTALL:
    case xPDOTransport::ACTION_UPGRADE:
        $c = $modx->newQuery('transport.modTransportPackage');
        $c->where([
            'workspace' => 1,
            "(SELECT
            `signature`
            FROM {$modx->getTableName('transport.modTransportPackage')} AS `latestPackage`
            WHERE `latestPackage`.`package_name` = `modTransportPackage`.`package_name`
            ORDER BY
                `latestPackage`.`version_major` DESC,
                `latestPackage`.`version_minor` DESC,
                `latestPackage`.`version_patch` DESC,
                IF(`release` = '' OR `release` = 'ga' OR `release` = 'pl','z',`release`) DESC,
                `latestPackage`.`release_index` DESC
                LIMIT 1,1) = `modTransportPackage`.`signature`",
        ]);
        $c->where([
            'modTransportPackage.signature:LIKE' => $options['namespace'] . '-%',
            'modTransportPackage.installed:IS NOT' => null
        ]);
        $c->limit(1);

        /** @var modTransportPackage $oldPackage */
        $oldPackage = $modx->getObject('transport.modTransportPackage', $c);

        if ($oldPackage && $oldPackage->compareVersion('1.4.0', '>')) {
            // Keep comparing the first characters of the not found URI in updated installations
            changeSettingValue($modx, 'customrequest.match_path_boundary', '0');
            $modx->log(xPDO::LOG_LEVEL_INFO, 'customrequest.match_path_boundary disabled to keep the previous alias path matching.');
        }
        break;
    case xPDOTransport::ACTION_UNINSTALL:
        break;
}
return $success;
