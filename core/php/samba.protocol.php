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

use Icewind\SMB\Server;
use Icewind\SMB\NativeServer;
use League\Flysystem\Filesystem;
use RobGridley\Flysystem\Smb\SmbAdapter;

class samba extends Fly {
  function __construct($_host, $_username, $_password, $_share) {
    $this->host = $_host;
	$this->username = $_username;
	$this->password = $_password;
    $this->share = $_share;
	$this->forceBase = false;
    $this->preciseProgress = true;
  }

  static function withEqLogic($_eqLogic) {
    return new self($_eqLogic->getConfiguration('host'), $_eqLogic->getConfiguration('username'), $_eqLogic->getConfiguration('password'), $_eqLogic->getConfiguration('share'));
  }
  
  function getFly($_base) {
    if (Server::nativeAvailable()) {
        $server = new NativeServer($this->host, $this->username, $this->password);
    } else {
        $server = new Server($this->host, $this->username, $this->password);
    }
    $share = $server->getShare($this->share);
    return new Filesystem(new SmbAdapter($share));
  }
}
