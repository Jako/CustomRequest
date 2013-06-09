<?php
/**
 * CustomRequest
 *
 * Copyright 2013 by Thomas Jakobi <thomas.jakobi@partout.info>
 *
 * CustomRequest is free software; you can redistribute it and/or modify it
 * under the terms of the GNU General Public License as published by the Free
 * Software Foundation; either version 2 of the License, or (at your option) any
 * later version.
 *
 * CustomRequest is distributed in the hope that it will be useful, but
 * WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or
 * FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more
 * details.
 *
 * You should have received a copy of the GNU General Public License along with
 * CustomRequest; if not, write to the Free Software Foundation, Inc.,
 * 59 Temple Place, Suite 330, Boston, MA 02111-1307 USA
 *
 * @package customrequest
 * @subpackage build
 *
 * System settings for the CustomRequest package.
 */
$settings = array();

// Area plugin
$settings['customrequest.debug'] = $modx->newObject('modSystemSetting');
$settings['customrequest.debug']->fromArray(array(
	'key' => 'customrequest.debug',
	'value' => '0',
	'xtype' => 'combo-boolean',
	'namespace' => 'customrequest',
	'area' => 'system',
		), '', true, true);
$settings['customrequest.configsPath'] = $modx->newObject('modSystemSetting');
$settings['customrequest.configsPath']->fromArray(array(
	'key' => 'customrequest.configsPath',
	'value' => '{core_path}components/customrequest/',
	'namespace' => 'customrequest',
	'area' => 'system',
		), '', true, true);

return $settings;
