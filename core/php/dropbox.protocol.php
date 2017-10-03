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

use Kunnu\Dropbox\DropboxApp;

use League\Flysystem\Filesystem;
use HemantMann\Flysystem\Dropbox\Adapter;

class dropbox extends DataTransfert {
  function __construct($_clientId, $_clientSecret, $_accessToken) {
    $this->clientId = $_clientId;
	$this->clientSecret = $_clientSecret;
	$this->accessToken = $_accessToken;
  }

  static function withEqLogic($_eqLogic) {
    return new self($_eqLogic->getConfiguration('clientId'), $_eqLogic->getConfiguration('clientSecret'), $_eqLogic->getConfiguration('accessToken'));
  }
  
  function put($_source, $_cible) {
    \log::add('datatransfert', 'debug', "uploading " . $_source . " to " . $_cible);
    $app = new DropboxApp($this->clientId, $this->clientSecret, $this->accessToken);
    $dropboxClient = new \Kunnu\Dropbox\Dropbox($app);
    $adapter = new Adapter($dropboxClient);
    $filesystem = new Filesystem($adapter);
	$filesystem->putStream(basename($_cible), fopen($_source, 'r'));
  }
}
