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

use Rclonewrapper\Rclonewrapper;

class rclone extends DataTransfert {
  function __construct($_config) {
    $this->config = $_config;
  }

  static function withEqLogic($_eqLogic) {
    return new self($_eqLogic->getConfiguration('rclone'));
  }
  
  function put($_source, $_cible) {
    \log::add('datatransfert', 'debug', "uploading " . $_source . " to " . $_cible);
	$rclone_path = dirname(__FILE__) . '/../../external/rclone/rclone';
	$rclone_config = tempnam(sys_get_temp_dir(),'rclone');
	file_put_contents($rclone_config, $this->config);
	$rclone = new Rclonewrapper($rclone_path, $rclone_config);
	\log::add('datatransfert', 'debug', "rclone version " . $rclone->version());
	\log::add('datatransfert', 'debug', "remotes " . json_encode($rclone->listremotes()));
	$rclone->setremote($rclone->listremotes()[0]);
	$rclone->copy($_source, dirname($_cible));
  }
}
