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

namespace DataTransfert;

/* * ***************************Includes********************************* */
require_once dirname(__FILE__) . '/../../../../core/php/core.inc.php';
require_once dirname(__FILE__) . '/../../core/php/datatransfert.inc.php';

class gdrive extends Fly {
  function __construct($_clientId, $_clientSecret, $_accessToken) {
    $this->clientId = $_clientId;
	$this->clientSecret = $_clientSecret;
	$this->accessToken = $_accessToken;
	$this->forceBase = false;
	$this->removeDupes = true;
  }

  static function withEqLogic($_eqLogic) {
    return new self($_eqLogic->getConfiguration('clientId'), $_eqLogic->getConfiguration('clientSecret'), $_eqLogic->getConfiguration('accessToken'));
  }
  
  function getFly($_base) {
    $client = new \Google_Client();
    $client->setClientId($this->clientId);
    $client->setClientSecret($this->clientSecret);
    $client->refreshToken($this->accessToken);

    $service = new \Google_Service_Drive($client);

    $adapter = new \Hypweb\Flysystem\GoogleDrive\GoogleDriveAdapter($service, $_base);
    return new \League\Flysystem\Filesystem($adapter);
  }
}
