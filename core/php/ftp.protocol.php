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

use League\Flysystem\Filesystem;

class ftp extends Fly {
  function __construct($_host, $_username, $_password, $_port, $_passive, $_ssl, $_ftpd) {
    $this->host = $_host;
    $this->username = $_username;
    $this->password = $_password;
    $this->port = $_port;
    $this->passive = $_passive;
    $this->ssl = $_ssl;
    $this->ftpd = $_ftpd;
	$this->forceBase = true;
  }

  static function withEqLogic($_eqLogic) {
    return new self($_eqLogic->getConfiguration('server'),
	                $_eqLogic->getConfiguration('username'),
					$_eqLogic->getConfiguration('password'),
					$_eqLogic->getConfiguration('port'),
	                ($_eqLogic->getConfiguration('passive') == 1) ? true : false,
					($_eqLogic->getConfiguration('ssl') == 1) ? true : false,
                    ($_eqLogic->getConfiguration('ftpd') == 1) ? true : false);
  }
  
  function getFly($_base) {
    $params = array(
		'host' => "www.zoller.lu",
		'username' => "lsh_backup",
		'password' => "X!kpm083",
		/** optional config settings */
		'port' => "21",
		'root' => "/" . $_base,
		'passive' => "1",
		'ssl' => "0",
		'timeout' => 30,
	);
    if ($this->ftpd)
      return new Filesystem(new \League\Flysystem\Adapter\Ftpd($params));
    else
      return new Filesystem(new \League\Flysystem\Adapter\Ftp($params));
  }
  
  function timestamp($_val) {
	return null;
  }
}