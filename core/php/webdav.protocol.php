<?php

/* This file is part of Jeedom.
 *
 * Jeedom is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Jeedom is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Jeedom. If not, see <http://www.gnu.org/licenses/>.
 */

/* * ***************************Includes********************************* */
require_once dirname(__FILE__) . '/../../../../core/php/core.inc.php';
require_once dirname(__FILE__) . '/../../core/php/datatransfert.inc.php';

function webdav_send($_eqLogic, $_source, $_cible, $_file) {
	$settings = array(
		'baseUri' => $_eqLogic->getConfiguration('baseUri'),
		'userName' => $_eqLogic->getConfiguration('userName'),
		'password' => $_eqLogic->getConfiguration('password'),
	);
	$client = new Sabre\DAV\Client($settings);
	if ($_eqLogic->getConfiguration('disableSslVerification', 0) == 1) {
		$client->addCurlSetting(CURLOPT_SSL_VERIFYPEER, false);
	}
	$adapter = new League\Flysystem\WebDAV\WebDAVAdapter($client, $_cible);
	$flysystem = new League\Flysystem\Filesystem($adapter);
	$flysystem->put($_file, file_get_contents($_source . '/' . $_file));
}
