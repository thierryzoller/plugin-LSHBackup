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

use League\Flysystem\Adapter\Ftp as Adapter;
use League\Flysystem\Filesystem;

class ftp extends Fly {
  function __construct($_host, $_username, $_password, $_port, $_passive, $_ssl) {
    $this->host = $_host;
    $this->username = $_username;
    $this->password = $_password;
    $this->port = $_port;
    $this->passive = $_passive;
    $this->ssl = $_ssl;
	$this->forceBase = true;
  }

  static function withEqLogic($_eqLogic) {
    return new self($_eqLogic->getConfiguration('server'),
	                $_eqLogic->getConfiguration('username'),
					$_eqLogic->getConfiguration('password'),
					$_eqLogic->getConfiguration('port'),
	                ($_eqLogic->getConfiguration('passive') == 1) ? true : false,
					($_eqLogic->getConfiguration('ssl') == 1) ? true : false);
  }
  
  function getFly($_base) {
    return new Filesystem(new Adapter(array(
		'host' => $this->host,
		'username' => $this->username,
		'password' => $this->password,
		/** optional config settings */
		'port' => $this->port,
		'root' => $_base,
		'passive' => $this->passive,
		'ssl' => $this->ssl,
		'timeout' => 30,
	)));
  }
  
  function timestamp($_val) {
	$formats = array("*-*-*.*.*-Y-m-d-H?i.*.*" => "backup-fensoft-3.1.5-2017-10-04-11h52.tar.gz");
	foreach ($formats as $format => $example) {
		$date = \DateTime::createFromFormat($format, $_val["basename"]);
		if ($date)
			return $date->format('U');
	}
	return null;
  }
}